<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado como administrador
if (!isset($_SESSION['id_admin']) || $_SESSION['tipo_usuario'] !== 'admin') {
    die("Acesso negado. Você não tem permissão para acessar esta página.");
}

require_once __DIR__ . '/../../backend/app/controller/PedidoParaUploaderController.php';
require_once __DIR__ . '/../../backend/app/core/Database.php';

$db = (new Database())->getConnection();
$pedidoUploaderController = new PedidoUploaderController($db);

// Obtém o status selecionado para o filtro (ou usa 'todos' como padrão)
$statusFiltro = $_GET['status'] ?? 'todos';

// Busca os pedidos com base no filtro
if ($statusFiltro === 'todos') {
    $pedidos = $pedidoUploaderController->buscarTodosPedidos();
} else {
    $pedidos = $pedidoUploaderController->buscarPedidosPorStatus($statusFiltro);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos de Uploader</title>
    <link rel="stylesheet" href="../css/ver_pedidos.css"> <!-- Seu CSS aqui -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .filtro-container {
            margin-bottom: 20px;
        }

        .filtro-container label {
            font-weight: bold;
            margin-right: 10px;
        }

        .filtro-container select {
            padding: 5px;
            font-size: 16px;
        }

        .pedido-container {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .pedido-container h3 {
            margin-top: 0;
        }

        .pedido-container .foto img {
            max-width: 30%;
            height: auto;
            border-radius: 5px;
        }

        .pedido-container .info p {
            margin: 5px 0;
        }

        .pedido-container .status {
            font-weight: bold;
        }

        .status.pendente {
            color: #ff9800;
            /* Laranja */
        }

        .status.aprovado {
            color: #4caf50;
            /* Verde */
        }

        .status.recusado {
            color: #f44336;
            /* Vermelho */
        }

        .pedido-container a {
            text-decoration: none;
            color: #2196f3;
            /* Azul */
            font-weight: bold;
        }

        .pedido-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>Pedidos de Uploader</h1>

    <!-- Filtro de status -->
    <div class="filtro-container">
        <form method="GET" action="">
            <label for="status">Filtrar por status:</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="todos" <?= $statusFiltro === 'todos' ? 'selected' : '' ?>>Todos</option>
                <option value="pendente" <?= $statusFiltro === 'pendente' ? 'selected' : '' ?>>Pendentes</option>
                <option value="aprovado" <?= $statusFiltro === 'aprovado' ? 'selected' : '' ?>>Aprovados</option>
                <option value="recusado" <?= $statusFiltro === 'recusado' ? 'selected' : '' ?>>Recusados</option>
            </select>
        </form>
    </div>

    <?php if (empty($pedidos)): ?>
        <p>Nenhum pedido encontrado.</p>
    <?php else: ?>
        <?php foreach ($pedidos as $pedido): ?>
            <div class="pedido-container">
                <h3>Pedido #<?= htmlspecialchars($pedido['id_pedido']) ?></h3>

                <!-- Foto -->
                <div class="foto">
                    <img src="../uploads/upload_pedido/<?= htmlspecialchars($pedido['foto_url']) ?>" alt="Foto">
                </div>

                <!-- Informações do pedido -->
                <div class="info">
                    <p><strong>Data do Pedido:</strong> <?= htmlspecialchars($pedido['data_pedido']) ?></p>
                    <p><strong>Link da Rede Social:</strong> <a href="<?= htmlspecialchars($pedido['link_rede_social']) ?>" target="_blank"><?= htmlspecialchars($pedido['link_rede_social']) ?></a></p>
                    <p><strong>Frase Favorita:</strong> <?= htmlspecialchars($pedido['frase_favorita'] ?? '') ?></p>
                    <p><strong>Status:</strong> <span class="status <?= htmlspecialchars($pedido['status']) ?>"><?= htmlspecialchars($pedido['status']) ?></span></p>
                </div>

                <!-- Link para detalhes do pedido -->
                <a href="detalhes_pedidos_upload.php">Ver Detalhes</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>