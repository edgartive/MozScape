<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado.");
}

require_once __DIR__ . '/../../backend/app/controller/PedidoUploaderController.php';
require_once __DIR__ . '/../../backend/app/core/Database.php';

$id_pedido = $_GET['id_pedido'] ?? null;
if (!$id_pedido) {
    die("ID do pedido inválido.");
}

$db = (new Database())->getConnection();
$pedidoUploaderController = new PedidoUploaderController($db);

if ($pedidoUploaderController->atualizarStatus($id_pedido, 'aprovado')) {
    header("Location: ver_pedidos.php");
    exit;
} else {
    die("Erro ao aprovar o pedido.");
}
