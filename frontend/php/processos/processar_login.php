<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/../../../backend/app/controller/UsuarioController.php';
require_once __DIR__ . '/../../../backend/app/core/Database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $senha = $_POST['password'];

    // Validação básica
    if (empty($username) || empty($senha)) {
        die("Por favor, preencha todos os campos.");
    }

    // Conecta ao banco de dados
    $db = (new Database())->getConnection();

    // Verifica se é um administrador
    $queryAdmin = "SELECT * FROM administradores WHERE username = :username";
    $stmtAdmin = $db->prepare($queryAdmin);
    $stmtAdmin->bindValue(':username', $username);
    $stmtAdmin->execute();
    $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

    if ($admin && $senha === $admin['senha']) {
        // Login bem-sucedido como administrador
        $_SESSION['id_admin'] = $admin['id_admin'];
        $_SESSION['tipo_usuario'] = 'admin'; // Define o tipo de usuário como admin
        header('Location: ../admin.php'); // Redireciona para a tela de admin
        exit;
    }

    // Verifica se é um usuário normal
    $queryUsuario = "SELECT * FROM usuarios WHERE username = :username";
    $stmtUsuario = $db->prepare($queryUsuario);
    $stmtUsuario->bindValue(':username', $username);
    $stmtUsuario->execute();
    $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Login bem-sucedido como usuário normal
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['tipo_usuario'] = 'usuario'; // Define o tipo de usuário como usuário normal
        header('Location: ../index.php'); // Redireciona para a tela de usuário
        exit;
    }

    // Se não encontrou em nenhuma tabela
    die("Username ou senha incorretos.");
} else {
    die("Método de requisição inválido.");
}
