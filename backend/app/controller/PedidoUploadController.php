<?php

use App\Model\PedidoUpload;

require_once __DIR__ . '/../model/dao/UploadDAO.php';
require_once __DIR__ . '/../model/dao/PedidoUploadDAO.php';
require_once __DIR__ . '/../model/PedidoUpload.php';

class PedidoUploadController
{
    private $pedidoUploadDAO;
    private $uploadDAO;
    public function __construct($db)
    {
        $this->pedidoUploadDAO = new PedidoUploadDAO($db);
        $this->uploadDAO = new UploadDAO($db);
    }
    public function criarPedido($id_usuario, $tipo, $descricao, $foto_temp, $data_pedido, $status = 'pendente')
    {
        try {
            $uploadDir = __DIR__ . '/../../uploads/pedidos/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Sanitiza o tipo
            $tiposPermitidos = ['natureza', 'retrato', 'paisagem', 'céu', 'carros', 'outros'];
            $tipo = in_array(strtolower($tipo), $tiposPermitidos) ? strtolower($tipo) : 'outros';

            $ext = pathinfo($foto_temp['name'], PATHINFO_EXTENSION);
            $nomeArquivo = 'user_' . $id_usuario . '_' . uniqid() . '.' . $ext;
            $caminhoCompleto = $uploadDir . $nomeArquivo;

            if (move_uploaded_file($foto_temp['tmp_name'], $caminhoCompleto)) {
                $pedido = new PedidoUpload();
                $pedido->setIdUsuario($id_usuario);
                $pedido->setTipo($tipo);
                $pedido->setDescricao($descricao);
                $pedido->setFotoUrl($nomeArquivo);
                $pedido->setDataPedido($data_pedido);
                $pedido->setStatus($status);

                $pedidoId = $this->pedidoUploadDAO->criarPedido($pedido);

                if ($pedidoId) {
                    return $pedidoId;
                } else {
                    unlink($caminhoCompleto);
                    throw new Exception("Falha ao registrar no banco de dados");
                }
            } else {
                throw new Exception("Falha ao mover arquivo");
            }
        } catch (Exception $e) {
            error_log("Erro no criarPedido: " . $e->getMessage());
            throw $e; // Re-lança a exceção para tratamento no frontend
        }
    }
    public function aprovarPedido($id_pedido)
    {
        // Buscar o pedido
        $pedido = $this->buscarPedidoPorId($id_pedido);
        if (!$pedido || $pedido['status'] !== 'pendente') {
            return false;
        }

        // Diretório para uploads aprovados
        $uploadDir = __DIR__ . '/../../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Mover arquivo para a pasta de uploads aprovados
        $origem = __DIR__ . '/../../uploads/pedidos/' . $pedido['foto_url'];
        $destino = $uploadDir . $pedido['foto_url'];

        if (rename($origem, $destino)) {
            // Atualizar status no banco de dados
            if ($this->pedidoUploadDAO->atualizarStatus($id_pedido, 'aprovado')) {
                return true;
            }
            // Se falhar no banco, move o arquivo de volta
            rename($destino, $origem);
        }
        return false;
    }
    public function buscarTodosPedidos()
    {
        return $this->pedidoUploadDAO->buscarTodosPedidos();
    }

    /**
     * Busca pedidos de um usuário específico
     */
    public function buscarPedidosPorUsuario($id_usuario)
    {
        return $this->pedidoUploadDAO->buscarPedidosPorUsuario($id_usuario);
    }

    /**
     * Busca um pedido específico por ID
     */
    public function buscarPedidoPorId($id_pedido)
    {
        return $this->pedidoUploadDAO->buscarPedidoPorId($id_pedido);
    }

    /**
     * Atualiza o status de um pedido
     */
    public function atualizarStatus($id_pedido, $status)
    {
        return $this->pedidoUploadDAO->atualizarStatus($id_pedido, $status);
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
}
