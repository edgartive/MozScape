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

    // Cria um novo pedido de uploader
    public function criarPedido(PedidoParaUploader $pedido)
    {
        #  var_dump($_SESSION); // Depurar para verificar o conteúdo da sessão
        $agora = new DateTime();
        $agora->format('Y - m - d');

        $query = "INSERT INTO pedidosuploader (id_usuario, foto1_url, foto2_url, foto3_url, frase_favorita, data_pedido, status) 
                  VALUES (:id_usuario, :foto1_url, :foto2_url, :foto3_url, :frase_favorita, :data_pedido, :status)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':id_usuario', $_SESSION['id_usuario'], PDO::PARAM_INT);
        $stmt->bindValue(':foto1_url', $pedido->getFoto1Url());
        $stmt->bindValue(':foto2_url', $pedido->getFoto2Url());
        $stmt->bindValue(':foto3_url', $pedido->getFoto3Url());
        $stmt->bindValue(':frase_favorita', $pedido->getFraseFavorita());
        $stmt->bindValue(':data_pedido',         $agora->format('Y - m - d'));
        $stmt->bindValue(':status', 'pendente');

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

    // Atualiza o status de um pedido
    public function atualizarStatus($id_pedido, $status)
    {
        $query = "UPDATE pedidosuploader SET status = :status WHERE id_pedido = :id_pedido";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id_pedido', $id_pedido);
        return $stmt->execute();
    }
}
