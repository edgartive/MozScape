<?php

use App\Model\PedidoUpload;

require_once __DIR__ . '/../model/dao/UploadDAO.php';
require_once __DIR__ . '/../model/dao/PedidoUploadDAO.php';
require_once __DIR__ . '/../model/PedidoUpload.php';

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
        $this->uploadDAO = new UploadDAO($db);
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
    // No PedidoUploadController.php
    public function aprovarPedido($id_pedido)
    {
        $pedido = $this->buscarPedidoPorId($id_pedido);
        if (!$pedido || $pedido['pedido']['status'] !== 'pendente') {
            return false;
        }

        // 1. Move a imagem para a pasta de aprovados
        $origem = __DIR__ . '/../../uploads/pedidos/' . $pedido['pedido']['foto_url'];
        $destino = __DIR__ . '/../../uploads/aprovados/' . $pedido['pedido']['foto_url'];

        if (!copy($origem, $destino)) {
            error_log("Falha ao copiar imagem: " . error_get_last()['message']);
            return false;
        }

        // 2. Atualiza status no banco
        return $this->pedidoUploadDAO->atualizarStatus($id_pedido, 'aprovado');
    }

    public function recusarPedido($id_pedido)
    {
        return $this->pedidoUploadDAO->atualizarStatus($id_pedido, 'recusado');
    }
}
