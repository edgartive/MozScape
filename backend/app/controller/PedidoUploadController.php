<?php

use App\Model\PedidoUpload;

require_once __DIR__ . '/../model/PedidoUpload.php';
require_once __DIR__ . '/../model/dao/PedidoUploadDAO.php';

class PedidoUploadController
{
    private $pedidoUploadDAO;

    public function __construct($db)
    {
        $this->pedidoUploadDAO = new PedidoUploadDAO($db);
    }

    // Cria um novo pedido de upload
    public function criarPedido($id_usuarios, $tipo, $descricao, $foto_url, $data_pedido, $status)
    {
        $pedido = new PedidoUpload();
        $pedido->setIdUsuario($id_usuarios);
        $pedido->setTipo($tipo);
        $pedido->setDescricao($descricao);
        $pedido->setFotoUrl($foto_url);
        $pedido->setDataPedido($data_pedido);
        $pedido->setStatus($status);

        return $this->pedidoUploadDAO->criarPedido($pedido);
    }

    // Busca todos os pedidos de upload
    public function buscarTodosPedidos()
    {
        return $this->pedidoUploadDAO->buscarTodosPedidos();
    }

    // Busca um pedido de upload por ID
    public function buscarPedidoPorId($id_pedido)
    {
        return $this->pedidoUploadDAO->buscarPedidoPorId($id_pedido);
    }

    // Atualiza o status de um pedido de upload
    public function atualizarStatus($id_pedido, $status)
    {
        return $this->pedidoUploadDAO->atualizarStatus($id_pedido, $status);
    }
}
