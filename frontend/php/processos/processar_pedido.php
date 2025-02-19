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
    if (empty($_POST['motivation']) || empty($_POST['favoritePhrase']) || empty($_FILES['photoUploads'])) {
        die("Por favor, preencha todos os campos e faça o upload de 3 fotos.");
    }

    // Verifica se exatamente 3 fotos foram enviadas
    if (count($_FILES['photoUploads']['name']) !== 3) {
        die("Por favor, faça o upload de exatamente 3 fotos.");
    }

    // Processa o upload das fotos
    $uploadDir = __DIR__ . '/../../uploads/pedidos_uploader/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fotoUrls = [];
    for ($i = 0; $i < 3; $i++) {
        $nomeArquivo = uniqid() . '_' . basename($_FILES['photoUploads']['name'][$i]);
        $caminhoFinal = $uploadDir . $nomeArquivo;

        if (move_uploaded_file($_FILES['photoUploads']['tmp_name'][$i], $caminhoFinal)) {
            $fotoUrls[] = $nomeArquivo;
        } else {
            die("Erro ao fazer upload da foto " . ($i + 1));
        }
    }

    // Cria o pedido
    $dataPedido = date('Y-m-d H:i:s');
    if ($pedidoUploaderController->criarPedido($id_usuario, $fotoUrls[0], $fotoUrls[1], $fotoUrls[2], $_POST['favoritePhrase'], $dataPedido)) {
        echo "Pedido enviado com sucesso! Aguarde a aprovação.";
        header('Location: ../uploadoverview.php ');
    } else {
        die("Erro ao enviar o pedido.");
    }
} else {
    die("Método de requisição inválido.");
}
