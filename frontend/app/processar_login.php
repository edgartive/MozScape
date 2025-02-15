<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../backend/app/controller/UsuarioController.php';
require_once __DIR__ . '/../backend/app/core/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $senha = trim($_POST['password']);

    if (empty($username) || empty($senha)) {
        die("Por favor, preencha todos os campos.");
    }

    try {
        $db = (new Database())->getConnection();
        $usuarioController = new UsuarioController($db);

        // Busca o usuário pelo username
        $usuario = $usuarioController->buscarUsuarioPorUsername($username);

        if ($usuario) {
            // Verifica se a senha está correta
            if (password_verify($senha, $usuario['senha'])) {
                // Senha correta: inicia a sessão
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['username'] = $usuario['username'];
                $_SESSION['email'] = $usuario['email'];

                // Redireciona para a página inicial
                header('Location: index.php');
                exit;
            } else {
                die("Senha incorreta. <a href='login.php'>Tente novamente</a>.");
            }
        } else {
            die("Usuário não encontrado. <a href='login.php'>Tente novamente</a>.");
        }
    } catch (Exception $e) {
        die("Erro ao processar o login. Tente novamente mais tarde.");
    }
} else {
    header('Location: login.php');
    exit;
}
