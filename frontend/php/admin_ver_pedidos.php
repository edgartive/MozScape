<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado. Faça login para continuar.");
}

require_once __DIR__ . '/../../backend/app/core/Database.php';

$db = (new Database())->getConnection();

// Busca os pedidos pendentes
$query = "SELECT id_pedido, nome AS uploader_nome, foto1_url, foto2_url, foto3_url, frase_favorita, data_pedido
          FROM pedidosuploader p
          JOIN usuarios  ON id_usuario = id_usuario
          WHERE p.status = 'pendente' ORDER BY p.data_pedido DESC";

$stmt = $db->prepare($query);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos Recebidos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        .pedido {
            background: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .pedido img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 5px;
        }

        .fotos {
            display: flex;
        }

        .info {
            margin-top: 10px;
        }

        .info strong {
            display: block;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Pedidos Recebidos</h2>
        <?php if (!empty($pedidos)) { ?>
            <?php foreach ($pedidos as $pedido) { ?>
                <div class="pedido">
                    <div class="fotos">
                        <img src="../../uploads/pedidos_uploader/<?php echo htmlspecialchars($pedido['foto1_url']); ?>" alt="Foto 1">
                        <img src="../../uploads/pedidos_uploader/<?php echo htmlspecialchars($pedido['foto2_url']); ?>" alt="Foto 2">
                        <img src="../../uploads/pedidos_uploader/<?php echo htmlspecialchars($pedido['foto3_url']); ?>" alt="Foto 3">
                    </div>
                    <div class="info">
                        <strong>Uploader:</strong> <?php echo htmlspecialchars($pedido['uploader_nome']); ?>
                        <strong>Data do Pedido:</strong> <?php echo htmlspecialchars($pedido['data_pedido']); ?>
                        <strong>Frase Favorita:</strong> <?php echo htmlspecialchars($pedido['frase_favorita']); ?>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>Nenhum pedido pendente no momento.</p>
        <?php } ?>
    </div>
</body>

</html>