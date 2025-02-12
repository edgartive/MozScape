<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

// Caminhos corrigidos usando __DIR__
require_once __DIR__ . '/../backend/app/controller/UsuarioController.php';
require_once __DIR__ . '/../backend/app/core/Database.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados do formulário
    $nome_completo = $_POST['nome_completo'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $username = $_POST['username'];
    $frase_favorita = $_POST['frase_favorita'] ?? ''; // Valor padrão se não for enviado
    $uploader = isset($_POST['uploader']) ? 1 : 0; // 1 se marcado, 0 se não
    $biografia = $_POST['biografia'] ?? ''; // Valor padrão se não for enviado
    $links = $_POST['links'] ?? ''; // Valor padrão se não for enviado

    // Processamento da foto de perfil
    if (isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] === UPLOAD_ERR_OK) {
        $foto_de_perfil = $_FILES['foto_de_perfil'];
        $nome_arquivo = uniqid() . '_' . basename($foto_de_perfil['name']); // Nome único para o arquivo
        $caminho_destino = __DIR__ . '/../uploads/profile_pics/' . $nome_arquivo;

        // Move o arquivo para a pasta de uploads
        if (move_uploaded_file($foto_de_perfil['tmp_name'], $caminho_destino)) {
            $foto_de_perfil_url = $nome_arquivo; // Salva o nome do arquivo no banco de dados
        } else {
            die("Erro ao fazer upload da foto de perfil.");
        }
    } else {
        die("Foto de perfil é obrigatória.");
    }

    // Conexão com o banco de dados
    $db = (new Database())->getConnection();
    $usuarioController = new UsuarioController($db);

    // Cria o usuário
    if ($usuarioController->criarUsuario($nome_completo, $email, $senha, $username, $foto_de_perfil_url, $frase_favorita, $uploader, $biografia, $links)) {
        header('Location: login.php'); // Redireciona para a tela de login após o cadastro
        exit;
    } else {
        die("Erro ao cadastrar usuário.");
    }
} else {
    die("Método de requisição inválido.");
}
