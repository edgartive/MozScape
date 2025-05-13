<?php
session_start();
require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/PedidoUploadController.php';

// Verifica admin
if ($_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

$db = (new Database())->getConnection();
$pedidoUploadController = new PedidoUploadController($db);

// Obtém ID do pedido
$id_pedido = $_GET['id'] ?? 0;
$detalhes = $pedidoUploadController->buscarPedidoPorId($id_pedido);

if (!$detalhes) {
    die("Pedido não encontrado");
}

$pedido = $detalhes['pedido'];
$autor = $detalhes['autor'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Detalhes do Pedido #<?= $pedido['id_pedido'] ?></title>
    <style>
        .detalhes-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .imagem-pedido {
            max-width: 100%;
            height: auto;
            margin-top: 15px;
        }

        .acoes {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="detalhes-container">
        <h1>Pedido #<?= $pedido['id_pedido'] ?></h1>

        <p><strong>Autor:</strong> <?= htmlspecialchars($autor['nome_completo']) ?></p>
        <p><strong>Data:</strong> <?= $pedido['data_pedido'] ?></p>
        <p><strong>Status:</strong> <?= ucfirst($pedido['status']) ?></p>
        <p><strong>Categoria:</strong> <?= ucfirst($pedido['tipo']) ?></p>
        <p><strong>Descrição:</strong></p>
        <p><?= nl2br(htmlspecialchars($pedido['descricao'])) ?></p>

        <div class="imagem-container">
            <p><strong>Imagem:</strong></p>
            <img src="/uploads/pedidos/<?= $pedido['foto_url'] ?>"
                alt="Imagem do pedido"
                class="imagem-pedido">
        </div>

        <?php if ($pedido['status'] === 'pendente'): ?>
            <div class="acoes">
                <a href="aprovar_pedido.php?id=<?= $pedido['id_pedido'] ?>"
                    class="btn-aprovar">Aprovar</a>
                <a href="recusar_pedido.php?id=<?= $pedido['id_pedido'] ?>"
                    class="btn-recusar">Recusar</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>