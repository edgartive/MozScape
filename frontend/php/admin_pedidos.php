<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id_admin']) || $_SESSION['tipo_usuario'] !== 'admin') {
    die("Acesso negado.");
}

require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/PedidoUploadController.php';
require_once __DIR__ . '/../../backend/app/controller/UsuarioController.php';

$db = (new Database())->getConnection();
$pedidoController = new PedidoUploadController($db);
$usuarioController = new UsuarioController($db);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'] ?? null;
    $acao = $_POST['acao'] ?? null;

    if ($id_pedido && $acao) {
        if ($acao === 'aprovar') {
            if ($pedidoController->aprovarPedido($id_pedido)) {
                $mensagem = "Pedido aprovado com sucesso!";
            } else {
                $erro = "Falha ao aprovar pedido. Verifique os logs.";
            }
        } elseif ($acao === 'rejeitar') {
            if ($pedidoController->atualizarStatus($id_pedido, 'recusado')) {
                $mensagem = "Pedido recusado com sucesso!";
            } else {
                $erro = "Falha ao recusar pedido";
            }
        }
    }
}


// Busca pedidos
$pedidos = $pedidoController->buscarTodosPedidosComUsuarios();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos de Upload</title>
    <style>
        .mensagem {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .sucesso {
            background-color: #d4edda;
            color: #155724;
        }

        .erro {
            background-color: #f8d7da;
            color: #721c24;
        }

        .visualizar-imagem {
            max-width: 200px;
            max-height: 200px;
            display: block;
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <h1>Pedidos de Upload</h1>

    <?php if (isset($mensagem)): ?>
        <div class="mensagem sucesso"><?= $mensagem ?></div>
    <?php endif; ?>

    <?php if (isset($erro)): ?>
        <div class="mensagem erro"><?= $erro ?></div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Data</th>
            <th>Categoria</th>
            <th>Imagem</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= $pedido['id_pedido'] ?></td>
                <td><?= htmlspecialchars($pedido['nome_completo']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                <td><?= ucfirst($pedido['tipo']) ?></td>
                <td>
                    <img src="../../uploads/pedidos/<?= $pedido['foto_url'] ?>"
                        class="visualizar-imagem"
                        alt="Pré-visualização">
                </td>
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
                    <a href="detalhes_pedidos.php?id=<?= $pedido['id_pedido'] ?>">Detalhes</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>