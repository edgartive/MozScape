<?php
session_start(); // Inicia a sessão
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../backend/app/controller/UsuarioController.php';
require_once __DIR__ . '/../backend/app/core/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $username = $_POST['username'];
    $senha = $_POST['password'];

    echo "Username fornecido: $username<br>";
    echo "Senha fornecida: $senha<br>";

    // Conecta ao banco de dados
    $db = (new Database())->getConnection();
    $usuarioController = new UsuarioController($db);

    // Busca o usuário pelo username
    $usuario = $usuarioController->buscarUsuarioPorUsername($username);

    if ($usuario) {
        echo "Usuário encontrado no banco de dados:<br>";
        print_r($usuario); // Exibe os dados do usuário
        echo "<br>Senha no banco: " . $usuario['senha'] . "<br>";

        // Verifica se a senha está correta
        if (password_verify($senha, $usuario['senha'])) {
            echo "Senha correta!<br>";

            // Credenciais corretas: inicia a sessão
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['email'] = $usuario['email'];

            // Redireciona para a página inicial
            header('Location: index.php');
            exit;
        } else {
            echo "Senha incorreta.<br>";
        }
    } else {
        echo "Usuário não encontrado.<br>";
    }

    // Credenciais inválidas: exibe mensagem de erro
    echo "Usuário ou senha incorretos. <a href='login.php'>Tente novamente</a>.";
} else {
    // Se o método não for POST, redireciona para o login
    header('Location: login.php');
    exit;
}
