<?php

use App\Model\PedidoUpload;

require_once __DIR__ . '/../PedidoUpload.php';
require_once __DIR__ . '/../../core/Database.php';

class PedidoUploadDAO
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Cria um novo pedido de upload
    public function criarPedido(PedidoUpload $pedido)
    {
        $query = "INSERT INTO PedidosUpload (id_usuarios, tipo, descricao, foto_url, data_pedido, status) 
                  VALUES (:id_usuarios, :tipo, :descricao, :foto_url, :data_pedido, :status)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':id_usuarios', $pedido->getIdUsuario());
        $stmt->bindValue(':tipo', $pedido->getTipo());
        $stmt->bindValue(':descricao', $pedido->getDescricao());
        $stmt->bindValue(':foto_url', $pedido->getFotoUrl());
        $stmt->bindValue(':data_pedido', $pedido->getDataPedido());
        $stmt->bindValue(':status', $pedido->getStatus());

        return $stmt->execute();
    }

    // Busca todos os pedidos de upload
    public function buscarTodosPedidos()
    {
        $query = "SELECT * FROM PedidosUpload ORDER BY data_pedido DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca um pedido de upload por ID
    public function buscarPedidoPorId($id_pedido)
    {
        $query = "SELECT * FROM PedidosUpload WHERE id_pedido = :id_pedido";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualiza o status de um pedido de upload
    public function atualizarStatus($id_pedido, $status)
    {
        $query = "UPDATE PedidosUpload SET status = :status WHERE id_pedido = :id_pedido";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
