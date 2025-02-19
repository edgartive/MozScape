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

    public function criarPedido($foto1_url, $foto2_url, $foto3_url, $frase_favorita, $data_pedido)
    {
        // Verificar se a sessão está iniciada e se o id_usuario está presente
        if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
            die("Erro: ID do usuário não encontrado.");
        }

        $id_usuario = $_SESSION['id_usuario']; // Pega o ID do usuário da sessão

        // Criar o objeto PedidoParaUploader
        $pedido = new PedidoParaUploader($id_usuario, $foto1_url, $foto2_url, $foto3_url, $frase_favorita, $data_pedido);

        // Chamar o método do DAO para criar o pedido
        return $this->pedidoUploaderDAO->criarPedido($pedido);
    }



    // Busca todos os pedidos de um usuário
    public function buscarPedidosPorUsuario($id_usuario)
    {
        return $this->pedidoUploaderDAO->buscarPedidosPorUsuario($id_usuario);
    }

    // Atualiza o status de um pedido
    public function atualizarStatus($id_pedido, $status)
    {
        return $this->pedidoUploaderDAO->atualizarStatus($id_pedido, $status);
    }
}
