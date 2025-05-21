<?php

use App\Model\PedidoUpload;

require_once __DIR__ . '/../model/dao/UploadDAO.php';
require_once __DIR__ . '/../model/dao/PedidoUploadDAO.php';
require_once __DIR__ . '/../model/PedidoUpload.php';
require_once __DIR__ . '/../model/Upload.php';
require_once __DIR__ . '/../controller/UsuarioController.php';

class PedidoUploadController
{
    private $db;
    private $pedidoUploadDAO;
    private $uploadDAO;
    public function __construct($db)
    {
        $this->db = $db;
        $this->pedidoUploadDAO = new PedidoUploadDAO($this->db);
        $this->uploadDAO = new UploadDAO($this->db);
    }

    public function buscarPedidosPorUsuario($id_usuario)
    {
        $query = "SELECT * FROM pedidosupload WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function criarPedido($id_usuario, $tipo, $descricao, $foto_temp, $data_pedido, $status = 'pendente')
    {
        // Configuração de diretórios
        $baseDir = realpath(__DIR__ . '/../../') . '/';
        $uploadDir = $baseDir . 'uploads/pedidos/';
        $backupDir = $baseDir . 'uploads/backups/';

        // Garante que os diretórios existam
        foreach ([$uploadDir, $backupDir] as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        // Geração de nome de arquivo único
        $ext = pathinfo($foto_temp['name'], PATHINFO_EXTENSION);
        $nomeArquivo = 'user_' . $id_usuario . '_' . uniqid() . '.' . $ext;
        $caminhoFinal = $uploadDir . $nomeArquivo;
        $caminhoBackup = $backupDir . $nomeArquivo;

        try {
            // Etapa 1: Faz upload para pasta temporária
            if (!move_uploaded_file($foto_temp['tmp_name'], $caminhoFinal)) {
                throw new Exception("Falha ao mover arquivo upload");
            }

            // Etapa 2: Cria cópia de backup
            if (!copy($caminhoFinal, $caminhoBackup)) {
                throw new Exception("Falha ao criar backup");
            }

            // Etapa 3: Registra no banco de dados
            $pedido = new PedidoUpload();
            $pedido->setIdUsuario($id_usuario);
            $pedido->setTipo($tipo);
            $pedido->setDescricao($descricao);
            $pedido->setFotoUrl($nomeArquivo);
            $pedido->setDataPedido($data_pedido);
            $pedido->setStatus($status);

            if (!$this->pedidoUploadDAO->criarPedido($pedido)) {
                throw new Exception("Falha no registro no banco");
            }

            return true;
        } catch (Exception $e) {
            // Limpeza em caso de erro
            if (file_exists($caminhoFinal)) unlink($caminhoFinal);
            if (file_exists($caminhoBackup)) unlink($caminhoBackup);

            error_log("ERRO CRÍTICO: " . $e->getMessage());
            return false;
        }
    }
    public function atualizarStatus($id_pedido, $status)
    {
        return $this->pedidoUploadDAO->atualizarStatus($id_pedido, $status);
    }
    public function buscarTodosPedidos($filtro = null)
    {
        try {
            // Verifica se há filtro
            if ($filtro && in_array($filtro, ['pendente', 'aprovado', 'recusado'])) {
                return $this->pedidoUploadDAO->buscarPedidosPorStatus($filtro);
            }

            // Retorna todos os pedidos se não houver filtro
            return $this->pedidoUploadDAO->buscarTodosPedidos();
        } catch (Exception $e) {
            error_log("Erro ao buscar pedidos: " . $e->getMessage());
            return [];
        }
    }

    // --- ALTERAÇÃO: Corrige o caminho da pasta de aprovados para frontend/uploads/aprovado ---
    public function aprovarPedido($id_pedido)
    {
        try {
            // 1. Busca o pedido no banco de dados
            $pedido = $this->pedidoUploadDAO->buscarPedidoPorId($id_pedido);

            if (!$pedido) {
                throw new Exception("Pedido não encontrado com ID: " . $id_pedido);
            }

            if ($pedido['status'] !== 'pendente') {
                throw new Exception("Pedido já processado (status atual: " . $pedido['status'] . ")");
            }

            // 2. Configuração de caminhos
            $baseDir = realpath(__DIR__ . '/../../') . '/';
            $pastaPedidos = $baseDir . 'uploads/pedidos/';
            // Caminho para frontend/uploads/aprovado
            $pastaAprovados = realpath($baseDir . '../frontend') . '/uploads/aprovado/';
            $nomeArquivo = $pedido['foto_url'];

            // Verificação de caminhos críticos
            if (empty($nomeArquivo)) {
                throw new Exception("Nome do arquivo de imagem está vazio");
            }

            // 3. Verifica/Cria pasta de aprovados
            if (!file_exists($pastaAprovados)) {
                if (!mkdir($pastaAprovados, 0755, true)) {
                    throw new Exception("Não foi possível criar a pasta de aprovados: " . $pastaAprovados);
                }
            }

            // 4. Define caminhos completos
            $caminhoOrigem = $pastaPedidos . $nomeArquivo;
            $caminhoDestino = $pastaAprovados . $nomeArquivo;

            // 5. Verificações de arquivo
            if (!file_exists($caminhoOrigem)) {
                // Tenta buscar no backup como fallback
                $backupDir = $baseDir . 'uploads/backups/';
                $caminhoBackup = $backupDir . $nomeArquivo;

                if (file_exists($caminhoBackup)) {
                    if (!copy($caminhoBackup, $caminhoOrigem)) {
                        throw new Exception("Arquivo não encontrado e falha ao restaurar backup");
                    }
                } else {
                    throw new Exception("Arquivo original não encontrado em: " . $caminhoOrigem);
                }
            }

            // 6. Move o arquivo com verificação
            if (!rename($caminhoOrigem, $caminhoDestino)) {
                throw new Exception("Falha ao mover arquivo para aprovados. Verifique permissões. Origem: " .
                    $caminhoOrigem . " Destino: " . $caminhoDestino);
            }

            // 7. Verifica se o arquivo foi movido com sucesso
            if (!file_exists($caminhoDestino)) {
                throw new Exception("Arquivo não apareceu no destino após mover");
            }

            // 8. Cria registro na tabela de uploads
            $upload = new Upload();
            $upload->setUsuarioId($pedido['id_usuario']);
            $upload->setTipo($pedido['tipo']);
            $upload->setDataUpload(date('Y-m-d H:i:s'));
            $upload->setDescricao($pedido['descricao']);
            $upload->setLikes(0);
            $upload->setFotoUrl($nomeArquivo);

            $idUpload = $this->uploadDAO->criarUpload($upload);

            if (!$idUpload) {
                // Reverte a movimentação do arquivo se falhar
                if (file_exists($caminhoDestino)) {
                    rename($caminhoDestino, $caminhoOrigem);
                }
                throw new Exception("Falha ao criar registro na tabela de uploads");
            }

            // 9. Atualiza status do pedido
            if (!$this->pedidoUploadDAO->atualizarStatus($id_pedido, 'aprovado')) {
                // Reverte tudo se falhar
                if (file_exists($caminhoDestino)) {
                    rename($caminhoDestino, $caminhoOrigem);
                }
                $this->uploadDAO->removerUpload($idUpload);
                throw new Exception("Falha ao atualizar status do pedido");
            }

            // 10. Log de sucesso
            error_log("Pedido aprovado com sucesso. ID: " . $id_pedido .
                " - Arquivo movido para: " . $caminhoDestino);

            return true;
        } catch (Exception $e) {
            // Log detalhado do erro
            error_log("ERRO AO APROVAR PEDIDO [" . $id_pedido . "]: " . $e->getMessage());
            error_log("StackTrace: " . $e->getTraceAsString());

            return false;
        }
    }
    public function aprovarPedido2($id_pedido)
    {
        try {
            // 1. Busca o pedido no banco de dados
            $pedido = $this->pedidoUploadDAO->buscarPedidoPorId($id_pedido);

            if (!$pedido) {
                throw new Exception("Pedido não encontrado com ID: " . $id_pedido);
            }

            if ($pedido['status'] !== 'pendente') {
                throw new Exception("Pedido já processado (status atual: " . $pedido['status'] . ")");
            }

            // 2. Configuração de caminhos
            $baseDir = realpath(__DIR__ . '/../../') . '/';
            $pastaPedidos = $baseDir . 'uploads/pedidos/';
            $pastaAprovados = $baseDir . 'uploads/aprovados/';
            $nomeArquivo = $pedido['foto_url'];

            // Verificação de caminhos críticos
            if (empty($nomeArquivo)) {
                throw new Exception("Nome do arquivo de imagem está vazio");
            }

            // 3. Verifica/Cria pasta de aprovados
            if (!file_exists($pastaAprovados)) {
                if (!mkdir($pastaAprovados, 0755, true)) {
                    throw new Exception("Não foi possível criar a pasta de aprovados: " . $pastaAprovados);
                }
            }

            // 4. Define caminhos completos
            $caminhoOrigem = $pastaPedidos . $nomeArquivo;
            $caminhoDestino = $pastaAprovados . $nomeArquivo;

            // 5. Verificações de arquivo
            if (!file_exists($caminhoOrigem)) {
                // Tenta buscar no backup como fallback
                $backupDir = $baseDir . 'uploads/backups/';
                $caminhoBackup = $backupDir . $nomeArquivo;

                if (file_exists($caminhoBackup)) {
                    if (!copy($caminhoBackup, $caminhoOrigem)) {
                        throw new Exception("Arquivo não encontrado e falha ao restaurar backup");
                    }
                } else {
                    throw new Exception("Arquivo original não encontrado em: " . $caminhoOrigem);
                }
            }

            // 6. Move o arquivo com verificação
            if (!rename($caminhoOrigem, $caminhoDestino)) {
                throw new Exception("Falha ao mover arquivo para aprovados. Verifique permissões. Origem: " .
                    $caminhoOrigem . " Destino: " . $caminhoDestino);
            }

            // 7. Verifica se o arquivo foi movido com sucesso
            if (!file_exists($caminhoDestino)) {
                throw new Exception("Arquivo não apareceu no destino após mover");
            }

            // 8. Cria registro na tabela de uploads
            $upload = new Upload();
            $upload->setUsuarioId($pedido['id_usuario']);
            $upload->setTipo($pedido['tipo']);
            $upload->setDataUpload(date('Y-m-d H:i:s'));
            $upload->setDescricao($pedido['descricao']);
            $upload->setLikes(0);
            $upload->setFotoUrl($nomeArquivo);

            $idUpload = $this->uploadDAO->criarUpload($upload);

            if (!$idUpload) {
                // Reverte a movimentação do arquivo se falhar
                if (file_exists($caminhoDestino)) {
                    rename($caminhoDestino, $caminhoOrigem);
                }
                throw new Exception("Falha ao criar registro na tabela de uploads");
            }

            // 9. Atualiza status do pedido
            if (!$this->pedidoUploadDAO->atualizarStatus($id_pedido, 'aprovado')) {
                // Reverte tudo se falhar
                if (file_exists($caminhoDestino)) {
                    rename($caminhoDestino, $caminhoOrigem);
                }
                $this->uploadDAO->removerUpload($idUpload);
                throw new Exception("Falha ao atualizar status do pedido");
            }

            // 10. Log de sucesso
            error_log("Pedido aprovado com sucesso. ID: " . $id_pedido .
                " - Arquivo movido para: " . $caminhoDestino);

            return true;
        } catch (Exception $e) {
            // Log detalhado do erro
            error_log("ERRO AO APROVAR PEDIDO [" . $id_pedido . "]: " . $e->getMessage());
            error_log("StackTrace: " . $e->getTraceAsString());

            return false;
        }
    }
    public function buscarPedidoPorId($id_pedido)
    {
        try {
            // Busca o pedido no DAO
            $pedido = $this->pedidoUploadDAO->buscarPedidoPorId($id_pedido);

            if (!$pedido) {
                throw new Exception("Pedido não encontrado");
            }

            // Busca informações adicionais do usuário
            $usuarioController = new UsuarioController($this->db);
            $autor = $usuarioController->buscarUsuarioPorId($pedido['id_usuario']);

            return [
                'pedido' => $pedido,
                'autor' => $autor
            ];
        } catch (Exception $e) {
            error_log("Erro ao buscar pedido por ID: " . $e->getMessage());
            return null;
        }
    }
    /**
     * Atualiza os dados de um pedido existente
     */
    public function atualizarPedido($id_pedido, $tipo, $descricao)
    {
        return $this->pedidoUploadDAO->atualizarPedido($id_pedido, $tipo, $descricao);
    }

    /**
     * Remove um pedido (cancelamento pelo usuário)
     */
    public function removerPedido($id_pedido)
    {
        return $this->pedidoUploadDAO->removerPedido($id_pedido);
    }
    public function criarUploadAprovado($id_pedido)
    {
        $pedido = $this->pedidoUploadDAO->buscarPedidoPorId($id_pedido);
        if (!$pedido || $pedido['status'] !== 'pendente') {
            return false;
        }

        $upload = new Upload();
        $upload->setUsuarioId($pedido['id_usuario']);
        $upload->setTipo($pedido['tipo']);
        $upload->setDataUpload(date('Y-m-d H:i:s'));
        $upload->setDescricao($pedido['descricao']);
        $upload->setLikes(0);
        $upload->setFotoUrl($pedido['foto_url']); // Mesmo nome de arquivo

        return $this->uploadDAO->criarUpload($upload);
    }

    public function recusarPedido($id_pedido)
    {
        return $this->pedidoUploadDAO->atualizarStatus($id_pedido, 'recusado');
    }
    public function buscarTodosPedidosComUsuarios()
    {
        $query = "SELECT p.*, u.nome_completo 
                  FROM PedidosUpload p
                  JOIN usuarios u ON p.id_usuario = u.id_usuario
                  ORDER BY p.data_pedido DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
