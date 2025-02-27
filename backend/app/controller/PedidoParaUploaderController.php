<?php

use App\Model\PedidoParaUploader as PedidoParaUploader;

require_once __DIR__ . '/../model/PedidoParaUploader.php';
require_once __DIR__ . '/../model/dao/PedidoUploaderDAO.php';

class PedidoUploaderController
{
    private $pedidoUploaderDAO;

    public function __construct($db)
    {
        $this->pedidoUploaderDAO = new PedidoUploaderDAO($db);
    }
    public function criarPedido($id_usuario, $foto_url, $link_rede_social, $frase_favorita, $data_pedido)
    {
        $pedido = new PedidoParaUploader($id_usuario, $foto_url, $link_rede_social, $frase_favorita, $data_pedido);
        return $this->pedidoUploaderDAO->criarPedido($pedido);
    }


    // Busca todos os pedidos de um usuário
    public function buscarPedidosPorUsuario($id_usuario)
    {
        return $this->pedidoUploaderDAO->buscarPedidosPorUsuario($id_usuario);
    }

    public function buscarPedidoPorId($id_pedido)
    {
        return $this->pedidoUploaderDAO->buscarPedidoPorId($id_pedido);
    }

    public function atualizarStatusEAceitar($id_pedido, $status, $id_usuario)
    {
        return $this->pedidoUploaderDAO->atualizarStatusEAceitar($id_pedido, $status, $id_usuario);
    }
    public function buscarTodosPedidos()
    {
        return $this->pedidoUploaderDAO->buscarTodosPedidos();
    }
    public function atualizarStatus($id_pedido, $status, $id_usuario)
    {
        return $this->pedidoUploaderDAO->atualizarStatus($id_pedido, $status, $id_usuario);
    }
    public function verificarPedidoExistente($id_usuario)
    {
        return $this->pedidoUploaderDAO->verificarPedidoExistente($id_usuario);
    }

    public function buscarStatusPedido($id_usuario)
    {
        return $this->pedidoUploaderDAO->buscarStatusPedido($id_usuario);
    }
    public function buscarPedidoPorUsuario($id_usuario)
    {
        return $this->pedidoUploaderDAO->buscarPedidoPorUsuario($id_usuario);
    }
    public function buscarPedidosPorStatus($status)
    {
        return $this->pedidoUploaderDAO->buscarPedidosPorStatus($status);
    }
}
