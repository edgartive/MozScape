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
require_once __DIR__ . '/../../backend/app/controller/UploadController.php';

$db = (new Database())->getConnection();
$pedidoUploadController = new PedidoUploadController($db);
$uploadController = new UploadController($db);

$id_pedido = $_GET['id_pedido'] ?? null;
if (!$id_pedido) {
    die("ID do pedido inválido.");
}

// Busca o pedido de upload por ID
$pedido = $pedidoUploadController->buscarPedidoPorId($id_pedido);
if (!$pedido) {
    die("Pedido não encontrado.");
}

// Processa a ação de aceitar ou rejeitar o pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? null;
    if ($acao === 'aceitar' || $acao === 'rejeitar') {
        $status = ($acao === 'aceitar') ? 'aprovado' : 'recusado';
        if ($pedidoUploadController->atualizarStatus($id_pedido, $status)) {
            // Se o pedido for aceito, cria um post na tabela UPLOAD
            if ($acao === 'aceitar') {
                $data_upload = date('Y-m-d H:i:s');
                $uploadController->criarUpload(
                    $pedido['id_usuarios'], // ID do usuário
                    $pedido['tipo'], // Tipo
                    $data_upload, // Data do upload
                    $pedido['descricao'], // Descrição
                    0, // Likes (inicialmente 0)
                    $pedido['foto_url'] // URL da foto
                );
            }
            header("Location: list_pedidos_upload.php");
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
    <title>Detalhes do Pedido de Upload</title>
</head>

<body>
    <h1>Detalhes do Pedido de Upload #<?= $pedido['id_pedido'] ?></h1>
    <p><strong>Categoria:</strong> <?= $pedido['tipo'] ?></p>
    <p><strong>Descrição:</strong> <?= $pedido['descricao'] ?></p>
    <p><strong>Status:</strong> <?= $pedido['status'] ?></p>
    <img src="<?= $pedido['foto_url'] ?>" alt="Foto" style="max-width: 100%;">

    <form method="POST">
        <button type="submit" name="acao" value="aceitar">Aceitar</button>
        <button type="submit" name="acao" value="rejeitar">Rejeitar</button>
    </form>
</body>

</html>