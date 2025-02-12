<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php'); // Redireciona para o login se não estiver logado
    exit;
}

// Inclui as dependências necessárias
require_once __DIR__ . '/../backend/app/controller/UsuarioController.php';
require_once __DIR__ . '/../backend/app/core/Database.php';

// Conecta ao banco de dados
$db = (new Database())->getConnection();
$usuarioController = new UsuarioController($db);

// Busca os dados do usuário logado
$usuario = $usuarioController->buscarUsuarioPorId($_SESSION['id_usuario']);

// Verifica se o usuário foi encontrado
if (!$usuario) {
    die("Usuário não encontrado.");
}

// Depuração: Exibe o caminho da foto
$caminho_foto = "../uploads/profile_pics/" . $usuario['foto_de_perfil_url'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="perfil.css">
    <title>Meu Perfil</title>
</head>

<body>
    <div class="profile-container">
        <div class="profile-header">
            <!-- Exibe a foto de perfil do usuário -->
            <img src="<?php echo $caminho_foto; ?>" alt="Foto de Perfil" class="profile-picture">
            <h2><?php echo htmlspecialchars($usuario['username']); ?></h2>
            <p class="profile-email"><?php echo htmlspecialchars($usuario['email']); ?></p>
        </div>
        <div class="profile-details">
            <div class="detail-item">
                <span class="detail-label">Nome completo:</span>
                <span class="detail-value"><?php echo htmlspecialchars($usuario['nome_completo']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Frase favorita:</span>
                <span class="detail-value"><?php echo htmlspecialchars($usuario['frase_favorita']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Biografia:</span>
                <span class="detail-value"><?php echo htmlspecialchars($usuario['biografia']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Links:</span>
                <span class="detail-value"><?php echo htmlspecialchars($usuario['links']); ?></span>
            </div>
        </div>
        <button class="edit-button" onclick="window.location.href='editar_perfil.php'">Editar Perfil</button>
    </div>
</body>

</html>