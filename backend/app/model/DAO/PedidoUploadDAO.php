<?php

use App\Model\PedidoUpload;

require __DIR__ . '/../PedidoUpload.php'; // Ensure this file defines the PedidoUpload class
require_once __DIR__ . '/../../core/Database.php';

class PedidoUploadDAO
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function criarPedido(PedidoUpload $pedido)
    {
        try {
            $query = "INSERT INTO PedidosUpload 
                 (id_usuario, tipo, descricao, foto_url, data_pedido, status) 
                 VALUES (:id_usuario, :tipo, :descricao, :foto_url, :data_pedido, :status)";

            $stmt = $this->db->prepare($query);

            // Bind dos parâmetros com verificação
            $stmt->bindValue(':id_usuario', $pedido->getIdUsuario(), PDO::PARAM_INT);
            $stmt->bindValue(':tipo', $pedido->getTipo() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':descricao', $pedido->getDescricao() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':foto_url', $pedido->getFotoUrl() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':data_pedido', $pedido->getDataPedido() ?? date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(':status', $pedido->getStatus() ?? 'pendente', PDO::PARAM_STR);

            $result = $stmt->execute();

            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("Erro ao criar pedido: " . print_r($errorInfo, true));
                throw new Exception("Database error: " . $errorInfo[2]);
            }

            return $this->db->lastInsertId(); // Retorna o ID do novo pedido
        } catch (PDOException $e) {
            error_log("PDO Exception: " . $e->getMessage());
            throw new Exception("Erro ao registrar pedido no banco de dados.");
        }
    }
    public function buscarPedidosPorUsuario($id_usuario)
    {
        $query = "SELECT * FROM PedidosUpload 
                 WHERE id_usuario = :id_usuario 
                 ORDER BY data_pedido DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function buscarPedidoPorId($id_pedido)
    {
        $query = "SELECT p.*, u.nome_completo as autor_nome 
              FROM PedidosUpload p
              JOIN usuarios u ON p.id_usuario = u.id_usuario
              WHERE p.id_pedido = :id_pedido";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarStatus($id_pedido, $status)
    {
        $query = "UPDATE PedidosUpload SET status = :status 
                 WHERE id_pedido = :id_pedido";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function buscarTodosPedidos()
    {
        $query = "SELECT p.*, u.nome_completo as autor 
              FROM PedidosUpload p
              JOIN usuarios u ON p.id_usuario = u.id_usuario
              ORDER BY p.data_pedido DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPedidosPorStatus($status)
    {
        $query = "SELECT p.*, u.nome_completo as autor 
              FROM PedidosUpload p
              JOIN usuarios u ON p.id_usuario = u.id_usuario
              WHERE p.status = :status
              ORDER BY p.data_pedido DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarPedido($id_pedido, $tipo, $descricao)
    {
        $query = "UPDATE PedidosUpload 
                 SET tipo = :tipo, descricao = :descricao 
                 WHERE id_pedido = :id_pedido AND status = 'pendente'";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function removerPedido($id_pedido)
    {
        // Primeiro verifica se existe e está pendente
        $pedido = $this->buscarPedidoPorId($id_pedido);

        if (!$pedido || $pedido['status'] !== 'pendente') {
            return false;
        }

        // Remove a foto associada
        $foto_path = __DIR__ . '/../../uploads/pedidos/' . $pedido['foto_url'];
        if (file_exists($foto_path)) {
            unlink($foto_path);
        }

        // Remove o pedido
        $query = "DELETE FROM PedidosUpload 
                 WHERE id_pedido = :id_pedido AND status = 'pendente'";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
