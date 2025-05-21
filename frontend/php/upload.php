<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado. Você não tem permissão para acessar esta página.");
}

require_once __DIR__ . '/../../backend/app/controller/UsuarioController.php';
require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/PedidoUploadController.php';

$id_usuario = $_SESSION['id_usuario'];
$db = (new Database())->getConnection();
$usuarioController = new UsuarioController($db);
$pedidoUploadController = new PedidoUploadController($db);

// Verifica se é uploader
$usuario = $usuarioController->buscarUsuarioPorId($id_usuario);
if ($usuario['uploader'] != 1) {
    die("Acesso negado. Você precisa ser um uploader para acessar esta página.");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tipo = $_POST['tipo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';

        if (empty($tipo) || empty($descricao) || empty($_FILES['foto'])) {
            throw new Exception("Preencha todos os campos e selecione uma foto.");
        }

        $data_pedido = date('Y-m-d H:i:s');
        $pedidoId = $pedidoUploadController->criarPedido(
            $_SESSION['id_usuario'],
            $tipo,
            $descricao,
            $_FILES['foto'],
            $data_pedido
        );

        header("Location: meus_pedidos.php?sucesso=1&id=" . $pedidoId);
        exit;
    } catch (Exception $e) {
        $erro = $e->getMessage();
        error_log("Erro no upload: " . $erro);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Foto</title>
    <link rel="stylesheet" href="../css/upload.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
        }

        textarea {
            height: 100px;
        }

        .erro {
            color: red;
        }

        .sucesso {
            color: green;
        }
    </style>
</head>

<body>
    <h1>Upload de Foto</h1>

    <?php if (isset($erro)): ?>
        <p class="erro"><?= $erro ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="tipo">Categoria:</label>
            <select name="tipo" id="tipo" required>
                <option value="natureza">Natureza</option>
                <option value="retrato">Retrato</option>
                <option value="paisagem">Paisagem</option>
                <option value="céu">Céu</option>
                <option value="carros">Carros</option>
                <option value="outros">Outros</option>
            </select>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" required></textarea>
        </div>

        <div class="form-group">
            <label for="foto">Foto:</label>
            <input type="file" name="foto" id="foto" accept="image/*" required>
        </div>

        <button type="submit">Enviar Pedido</button>
    </form>
</body>

</html>