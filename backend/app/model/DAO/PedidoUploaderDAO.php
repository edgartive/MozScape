<?php

use App\Model\PedidoParaUploader;

require_once __DIR__ . '/../../model/PedidoParaUploader.php';
require_once __DIR__ . '/../../core/Database.php';

class PedidoUploaderDAO
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function verificarUsuarioExistente($id_usuario)
    {
        $query = "SELECT COUNT(*) FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0; // Retorna true se o usuário existir
    }
    public function criarPedido(PedidoParaUploader $pedido)
    {
        $query = "INSERT INTO pedidosuploader (id_usuario, foto_url, link_rede_social, frase_favorita, data_pedido, status) 
                  VALUES (:id_usuario, :foto_url, :link_rede_social, :frase_favorita, :data_pedido, :status)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':id_usuario', $pedido->getIdUsuario());
        $stmt->bindValue(':foto_url', $pedido->getFotoUrl());
        $stmt->bindValue(':link_rede_social', $pedido->getLinkRedeSocial());
        $stmt->bindValue(':frase_favorita', $pedido->getFraseFavorita());
        $stmt->bindValue(':data_pedido', $pedido->getDataPedido());
        $stmt->bindValue(':status', $pedido->getStatus());

        return $stmt->execute();
    }


    // Busca todos os pedidos de um usuário
    public function buscarPedidosPorUsuario($id_usuario)
    {
        $query = "SELECT * FROM pedidosuploader WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPedidoPorId($id_pedido)
    {
        $query = "SELECT * FROM pedidosuploader WHERE id_pedido = :id_pedido";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pedido', $id_pedido);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarStatusEAceitar($id_pedido, $status, $id_usuario)
    {
        // Atualiza o status do pedido
        $query = "UPDATE pedidosuploader SET status = :status WHERE id_pedido = :id_pedido";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id_pedido', $id_pedido);

        if ($stmt->execute()) {
            // Se o status for "aprovado", atualiza o atributo `uploader` do usuário
            if ($status === 'aprovado') {
                $queryUsuario = "UPDATE usuarios SET uploader = 1 WHERE id_usuario = :id_usuario";
                $stmtUsuario = $this->db->prepare($queryUsuario);
                $stmtUsuario->bindValue(':id_usuario', $id_usuario);
                return $stmtUsuario->execute();
            }
            return true;
        }
        return false;
    }
    public function buscarTodosPedidos()
    {
        // Consulta com JOIN para buscar o nome_completo ou username da tabela usuarios
        $query = "SELECT p.*, u.nome_completo, u.username 
                  FROM pedidosuploader p 
                  JOIN usuarios u ON p.id_usuario = u.id_usuario 
                  ORDER BY p.data_pedido DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPedidoPorUsuario($id_usuario)
    {
        $query = "SELECT * 
                  FROM pedidosuploader 
                  WHERE id_usuario = :id_usuario 
                  ORDER BY data_pedido DESC 
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function verificarPedidoExistente($id_usuario)
    {
        $query = "SELECT COUNT(*) 
                  FROM pedidosuploader 
                  WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0; // Retorna true se houver um pedido
    }

    public function buscarStatusPedido($id_usuario)
    {
        $query = "SELECT status 
                  FROM pedidosuploader 
                  WHERE id_usuario = :id_usuario 
                  ORDER BY data_pedido DESC 
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn(); // Retorna o status do pedido (pendente, aprovado, recusado)
    }
    public function atualizarStatus($id_pedido, $status, $id_usuario)
    {
        // Inicia uma transação para garantir que ambas as operações sejam executadas corretamente
        $this->db->beginTransaction();

        try {
            // Atualiza o status do pedido
            $queryPedido = "UPDATE pedidosuploader SET status = :status WHERE id_pedido = :id_pedido";
            $stmtPedido = $this->db->prepare($queryPedido);
            $stmtPedido->bindValue(':status', $status, PDO::PARAM_STR); // Garante que o valor seja tratado como string
            $stmtPedido->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmtPedido->execute();

            // Se o status for "aprovado", atualiza a coluna `uploader` do usuário para 1
            if ($status === 'aprovado') {
                $queryUsuario = "UPDATE usuarios SET uploader = 1 WHERE id_usuario = :id_usuario";
                $stmtUsuario = $this->db->prepare($queryUsuario);
                $stmtUsuario->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmtUsuario->execute();
            }

            // Se o status for "recusado", garante que a coluna `uploader` do usuário seja 0
            if ($status === 'recusado') {
                $queryUsuario = "UPDATE usuarios SET uploader = 0 WHERE id_usuario = :id_usuario";
                $stmtUsuario = $this->db->prepare($queryUsuario);
                $stmtUsuario->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmtUsuario->execute();
            }

            // Confirma a transação
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            // Em caso de erro, desfaz a transação
            $this->db->rollBack();
            error_log("Erro ao atualizar status do pedido: " . $e->getMessage()); // Log do erro
            return false;
        }
    }
    public function buscarPedidosPorStatus($status)
    {
        $query = "SELECT * FROM pedidosuploader WHERE status = :status ORDER BY data_pedido DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
