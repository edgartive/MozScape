<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado.");
}

require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/PedidoUploadController.php';

$db = (new Database())->getConnection();
$pedidoController = new PedidoUploadController($db);

$pedidos = $pedidoController->buscarPedidosPorUsuario($_SESSION['id_usuario']);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .pendente {
            color: orange;
        }

        .aprovado {
            color: green;
        }

        .recusado {
            color: red;
        }
    </style>
</head>

<body>
    <h1>Meus Pedidos de Upload</h1>

    <?php if (isset($_GET['sucesso'])): ?>
        <p style="color:green;">Pedido enviado com sucesso!</p>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Categoria</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= $pedido['id_pedido'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                <td><?= ucfirst($pedido['tipo']) ?></td>
                <td class="<?= $pedido['status'] ?>"><?= ucfirst($pedido['status']) ?></td>
                <td>
                    <?php if ($pedido['status'] == 'pendente'): ?>
                        <a href="editar_pedido.php?id=<?= $pedido['id_pedido'] ?>">Editar</a> |
                        <a href="cancelar_pedido.php?id=<?= $pedido['id_pedido'] ?>">Cancelar</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>