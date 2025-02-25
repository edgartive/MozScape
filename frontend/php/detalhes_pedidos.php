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

$id_pedido = $_GET['id_pedido'] ?? null;
if (!$id_pedido) {
    die("ID do pedido inválido.");
}

$db = (new Database())->getConnection();
$pedidoUploaderController = new PedidoUploaderController($db);

// Busca o pedido por ID
$pedido = $pedidoUploaderController->buscarPedidoPorId($id_pedido);
if (!$pedido) {
    die("Pedido não encontrado.");
}

// Processa a ação de aceitar ou rejeitar o pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? null;
    if ($acao === 'aceitar' || $acao === 'rejeitar') {
        $status = ($acao === 'aceitar') ? 'aprovado' : 'recusado';
        if ($pedidoUploaderController->atualizarStatus($id_pedido, $status)) {
            header("Location: ver_pedidos.php");
            exit;
        } else {
            die("Erro ao processar a ação.");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido</title>
    <link rel="stylesheet" href="../css/detalhes_pedido.css"> <!-- Seu CSS aqui -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .detalhes-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        .detalhes-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .detalhes-container .foto {
            text-align: center;
            margin-bottom: 20px;
        }

        .detalhes-container .foto img {
            max-width: 60%;
            height: auto;
            border-radius: 8px;
        }

        .detalhes-container .info {
            margin-bottom: 20px;
        }

        .detalhes-container .info p {
            margin: 10px 0;
        }

        .detalhes-container .acoes {
            display: flex;
            justify-content: space-between;
        }

        .detalhes-container .acoes button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .detalhes-container .acoes button.aceitar {
            background-color: #28a745;
            color: #fff;
        }

        .detalhes-container .acoes button.rejeitar {
            background-color: #dc3545;
            color: #fff;
        }

        .detalhes-container .acoes button:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <div class="detalhes-container">
        <h2>Detalhes do Pedido #<?= htmlspecialchars($pedido['id_pedido']) ?></h2>

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

        <!-- Botões de Ação -->
        <form method="POST" class="acoes">
            <button type="submit" name="acao" value="aceitar" class="aceitar">Aceitar</button>
            <button type="submit" name="acao" value="rejeitar" class="rejeitar">Rejeitar</button>
        </form>
    </div>
</body>

</html>