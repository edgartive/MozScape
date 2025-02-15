<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php'); // Redireciona para o login se não estiver logado
    exit;
}

// Inclui as dependências necessárias
require_once __DIR__ . '/../../backend/app/controller/UsuarioController.php';
require_once __DIR__ . '/../../backend/app/core/Database.php';

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
$caminho_foto = "../../uploads/profile_pics/" . $usuario['foto_de_perfil_url'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="../fontes/webfonts/css/all.css">

    <title>Meu Perfil</title>
</head>

<body>
    <div class="profile-container">
        <div class="profile-header">
            <!-- Exibe a foto de perfil do usuário -->

            <div class="profile-picture-container">
                <img src="<?php echo $caminho_foto; ?>" alt="Foto de Perfil" class="profile-picture">
                <!-- Ícone de edição -->
                <div class="edit-icon" onclick="togglePhotoOptions()">
                    <i class="fas fa-pen"></i>
                </div>

                <!-- Opções de ação -->
                <div class="photo-options" id="photo-options">
                    <button onclick="viewPhoto()">Ver Foto</button>
                    <button onclick="openEditPhoto()">Trocar Foto</button>
                </div>
            </div>

            <script>
                function togglePhotoOptions() {
                    document.getElementById("photo-options").classList.toggle("show");
                }

                function viewPhoto() {
                    window.open(document.getElementById("profile-picture-preview").src, "_blank");
                }

                function openEditPhoto() {
                    window.location.href = "editarFotoPerfil.php";
                }
            </script>

            <style>
                .profile-picture-container {
                    position: relative;
                    display: inline-block;
                }

                .edit-icon {
                    position: absolute;
                    bottom: 5px;
                    right: 5px;
                    background: rgba(0, 0, 0, 0.6);
                    color: white;
                    border-radius: 50%;
                    padding: 5px;
                    cursor: pointer;
                }

                .photo-options {
                    display: none;
                    position: absolute;
                    bottom: 40px;
                    right: 0;
                    background: white;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                    padding: 10px;
                }

                .photo-options.show {
                    display: block;
                }

                .photo-options button {
                    display: block;
                    background: none;
                    border: none;
                    padding: 5px;
                    width: 100%;
                    text-align: left;
                    cursor: pointer;
                }

                .photo-options button:hover {
                    background: #f0f0f0;
                }
            </style>

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
        <button class="edit-button" onclick="window.location.href='editPerfil.php'">Editar Perfil</button>
    </div>
</body>

</html>