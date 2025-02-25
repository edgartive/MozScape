<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado. Faça login para continuar.");
}

require_once __DIR__ . '/../../../backend/app/controller/PedidoParaUploaderController.php';
require_once __DIR__ . '/../../../backend/app/core/Database.php';

$id_usuario = $_SESSION['id_usuario'];
$db = (new Database())->getConnection();
$pedidoUploaderController = new PedidoUploaderController($db);

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação dos campos
    if (empty($_POST['motivation']) || empty($_POST['favoritePhrase']) || empty($_FILES['photoUpload']) || empty($_POST['socialLink'])) {
        die("Por favor, preencha todos os campos e faça o upload da foto.");
    }

    // Processa o upload da foto
    $uploadDir = __DIR__ . '/../../uploads/upload_pedido/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $nomeArquivo = uniqid() . '_' . basename($_FILES['photoUpload']['name']);
    $caminhoFinal = $uploadDir . $nomeArquivo;

    if (move_uploaded_file($_FILES['photoUpload']['tmp_name'], $caminhoFinal)) {
        // Cria o pedido
        $dataPedido = date('Y-m-d H:i:s');
        if ($pedidoUploaderController->criarPedido($id_usuario, $nomeArquivo, $_POST['socialLink'], $_POST['favoritePhrase'], $dataPedido)) {
            echo "Pedido enviado com sucesso! Aguarde a aprovação.";
            header('Location: ../index.php');
        } else {
            die("Erro ao enviar o pedido.");
        }
    } else {
        die("Erro ao fazer upload da foto.");
    }
} else {
    die("Método de requisição inválido.");
}
