<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado como admin
if (!isset($_SESSION['id_admin']) || $_SESSION['tipo_usuario'] !== 'admin') {
    die("Acesso negado. Você não tem permissão para acessar esta página.");
}

require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/PedidoUploadController.php';

$db = (new Database())->getConnection();
$pedidoUploadController = new PedidoUploadController($db);

// Busca todos os pedidos de upload
$pedidos = $pedidoUploadController->buscarTodosPedidos();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos de Upload</title>
</head>

<body>
    <h1>Pedidos de Upload</h1>
    <?php if (empty($pedidos)): ?>
        <p>Nenhum pedido de upload encontrado.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($pedidos as $pedido): ?>
                <li>
                    <a href="detalhes_pedidos_upload.php">
                        Pedido #<?= $pedido['id_pedido'] ?> - <?= $pedido['tipo'] ?> (<?= $pedido['status'] ?>)
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>

</html>