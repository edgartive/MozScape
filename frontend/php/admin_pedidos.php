<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id_admin']) || $_SESSION['tipo_usuario'] !== 'admin') {
    die("Acesso negado.");
}

require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/PedidoUploadController.php';
require_once __DIR__ . '/../../backend/app/controller/UploadController.php';

$db = (new Database())->getConnection();
$pedidoController = new PedidoUploadController($db);
$uploadController = new UploadController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'] ?? null;
    $acao = $_POST['acao'] ?? null;

    if ($id_pedido && $acao) {
        if ($acao === 'aprovar') {
            // Substitua a chamada problemática por:
            $detalhesPedido = $pedidoUploadController->buscarPedidoPorId($_GET['id']);

            if (!$detalhesPedido) {
                die("Pedido não encontrado ou erro ao buscar informações");
            }

            // Agora você pode acessar:
            $pedido = $detalhesPedido['pedido'];
            $autor = $detalhesPedido['autor'];
            $uploadController->criarUpload(
                $pedido['id_usuario'],
                $pedido['tipo'],
                date('Y-m-d H:i:s'),
                $pedido['descricao'],
                0,
                $pedido['foto_url']
            );

            // Atualiza status
            $pedidoController->atualizarStatus($id_pedido, 'aprovado');
        } elseif ($acao === 'rejeitar') {
            $pedidoController->atualizarStatus($id_pedido, 'recusado');
        }
    }
}

$pedidos = $pedidoController->buscarTodosPedidos();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos de Upload</title>
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

        .acoes form {
            display: inline;
        }
    </style>
</head>

<body>
    <h1>Pedidos de Upload</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Data</th>
            <th>Categoria</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= $pedido['id_pedido'] ?></td>
                <td><?= $pedido['id_usuario'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                <td><?= ucfirst($pedido['tipo']) ?></td>
                <td class="<?= $pedido['status'] ?>"><?= ucfirst($pedido['status']) ?></td>
                <td class="acoes">
                    <?php if ($pedido['status'] == 'pendente'): ?>
                        <form method="POST">
                            <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">
                            <button type="submit" name="acao" value="aprovar">Aprovar</button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido'] ?>">
                            <button type="submit" name="acao" value="rejeitar">Rejeitar</button>
                        </form>
                    <?php endif; ?>
                    <a href="detalhes_pedido.php?id=<?= $pedido['id_pedido'] ?>">Detalhes</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>