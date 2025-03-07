<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado e é um uploader
if (!isset($_SESSION['id_usuario']) || $_SESSION['uploader'] != 1) {
    die("Acesso negado. Você não tem permissão para acessar esta página.");
}

require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/PedidoUploadController.php';

$db = (new Database())->getConnection();
$pedidoUploadController = new PedidoUploadController($db);

// Processa o formulário de upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $foto_url = ''; // Aqui você deve processar o upload da foto e salvar o caminho

    if (!empty($tipo) && !empty($descricao) && !empty($_FILES['foto'])) {
        // Processa o upload da foto
        $uploadDir = __DIR__ . '/../../uploads/';
        $foto_url = uniqid() . '_' . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $uploadDir . $foto_url);

        // Cria o pedido de upload
        $data_pedido = date('Y-m-d H:i:s');
        if ($pedidoUploadController->criarPedido($_SESSION['id_usuario'], $tipo, $descricao, $foto_url, $data_pedido, 'pendente')) {
            echo "Pedido de upload enviado com sucesso! Aguarde a aprovação.";
        } else {
            die("Erro ao enviar o pedido de upload.");
        }
    } else {
        die("Por favor, preencha todos os campos e faça o upload da foto.");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Foto</title>
</head>

<body>
    <h1>Upload de Foto</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="tipo">Categoria:</label>
        <select name="tipo" id="tipo" required>
            <option value="natureza">Natureza</option>
            <option value="retrato">Retrato</option>
            <option value="paisagem">Paisagem</option>
            <option value="outros">Outros</option>
        </select><br><br>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" required></textarea><br><br>

        <label for="foto">Foto:</label>
        <input type="file" name="foto" id="foto" required><br><br>

        <button type="submit">Enviar Pedido</button>
    </form>
</body>

</html>