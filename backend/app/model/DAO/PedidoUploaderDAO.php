<?php

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../model/PedidoParaUploader.php';

class PedidoUploaderDAO
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Criar um novo pedido de uploader
    public function criarPedido(PedidoParaUploader $pedido)
    {
        $query = "INSERT INTO pedido_uploader (id_usuario, foto1_url, foto2_url, foto3_url, frase_favorita, data_pedido, status) 
                  VALUES (:id_usuario, :foto1_url, :foto2_url, :foto3_url, :frase_favorita, NOW(), 'pendente')";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':id_usuario', $pedido->getIdUsuario(), PDO::PARAM_INT);
        $stmt->bindValue(':foto1_url', $pedido->getFoto1Url());
        $stmt->bindValue(':foto2_url', $pedido->getFoto2Url());
        $stmt->bindValue(':foto3_url', $pedido->getFoto3Url());
        $stmt->bindValue(':frase_favorita', $pedido->getFraseFavorita());

        return $stmt->execute();
    }

    // Buscar pedido por ID
    public function buscarPedidoPorId($id_pedido)
    {
        $query = "SELECT * FROM pedido_uploader WHERE id_pedido = :id_pedido";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar todos os pedidos pendentes
    public function buscarPedidosPendentes()
    {
        $query = "SELECT * FROM pedido_uploader WHERE status = 'pendente'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Atualizar status do pedido
    public function atualizarStatusPedido($id_pedido, $status)
    {
        $query = "UPDATE pedido_uploader SET status = :status WHERE id_pedido = :id_pedido";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
