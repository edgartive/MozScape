<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/UploadController.php';
require_once __DIR__ . '/../../backend/app/controller/UsuarioController.php';

// Conexão com o banco de dados
$db = (new Database())->getConnection();
$uploadController = new UploadController($db);
$usuarioController = new UsuarioController($db);

// Obtém o ID do perfil a ser visualizado (pode ser o próprio usuário ou outro)
$id_perfil = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0);

// Redireciona para login se não estiver logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

// Busca informações do perfil
$usuarioPerfil = $usuarioController->buscarUsuarioPorId($id_perfil);
if (!$usuarioPerfil) {
    $usuarioPerfil = [
        'nome_completo' => 'Usuário não encontrado',
        'email_publico' => '',
        'bio' => '',
        'foto_de_perfil_url' => 'default.jpg'
    ];
}

// Busca os uploads do perfil
$uploads = $uploadController->listarUploadsPorUsuario($id_perfil);

// Verifica se o usuário logado é o dono do perfil
$isDonoPerfil = isset($_SESSION['id_usuario']) && ($_SESSION['id_usuario'] == $id_perfil);

// Caminho da foto de perfil
$caminho_foto = "../uploads/profile_pics/" . $usuarioPerfil['foto_de_perfil_url'];
$caminho_absoluto = $_SERVER['DOCUMENT_ROOT'] . '/MozScape/frontend/uploads/profile_pics/' . $usuarioPerfil['foto_de_perfil_url'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($usuarioPerfil['nome_completo']) ?> | MozScape</title>
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="../fontes/webfonts/css/all.css">
    <style>
        .profile-details {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .detail-item {
            margin-bottom: 1rem;
        }

        .detail-label {
            font-weight: bold;
            display: block;
            color: #3498db;
        }

        .edit-button:hover {
            background: #2980b9;
        }

        .galeria-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }

        .upload-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .upload-card:hover {
            transform: translateY(-5px);
        }

        .upload-imagem {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .upload-info {
            padding: 1rem;
        }

        .sem-uploads {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-picture-container">
                <img src="<?= file_exists($caminho_absoluto) ? $caminho_foto : '../uploads/profile_pics/default.jpg' ?>"
                    alt="Foto de Perfil"
                    class="profile-picture">

                <?php if ($isDonoPerfil): ?>
                    <div class="edit-icon" onclick="window.location.href='editarFotoPerfil.php'">
                        <i class="fas fa-pen"></i>
                    </div>
                <?php endif; ?>
            </div>

            <h1><?= htmlspecialchars($usuarioPerfil['nome_completo']) ?></h1>
            <p class="profile-email"><?= htmlspecialchars($usuarioPerfil['email_publico'] ?? '') ?></p>
        </div>

        <div class="profile-details">
            <?php if (!empty($usuarioPerfil['bio'])): ?>
                <div class="detail-item">
                    <span class="detail-label">Biografia:</span>
                    <p><?= nl2br(htmlspecialchars($usuarioPerfil['bio'])) ?></p>
                </div>
            <?php endif; ?>

            <div class="detail-item">
                <span class="detail-label">Total de Publicações:</span>
                <span><?= count($uploads) ?></span>
            </div>

            <?php if ($isDonoPerfil): ?>
                <button class="edit-button" onclick="window.location.href='editPerfil.php'">
                    <i class="fas fa-edit"></i> Editar Perfil
                </button>
            <?php endif; ?>
        </div>

        <h2>Publicações</h2>
        <div class="galeria-container">
            <?php if (!empty($uploads)): ?>
                <?php foreach ($uploads as $upload):
                    $caminho_foto_upload = '../uploads/aprovado/' . $upload['foto_url'];
                    $caminho_absoluto_upload = $_SERVER['DOCUMENT_ROOT'] . '/MozScape/frontend/uploads/aprovado/' . $upload['foto_url'];
                ?>
                    <div class="upload-card" onclick="window.location.href='detalhes_upload.php?id=<?= $upload['id_upload'] ?>'">
                        <?php if (file_exists($caminho_absoluto_upload)): ?>
                            <img src="<?= $caminho_foto_upload ?>"
                                alt="<?= htmlspecialchars($upload['descricao']) ?>"
                                class="upload-imagem">
                        <?php else: ?>
                            <div style="height:200px; background:#eee; display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-image" style="font-size:3rem; color:#999;"></i>
                            </div>
                        <?php endif; ?>

                        <div class="upload-info">
                            <p><?= htmlspecialchars($upload['descricao']) ?></p>
                            <div style="color: #666;">
                                <i class="fas fa-heart"></i> <?= $upload['likes'] ?> likes
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="sem-uploads">
                    <i class="fas fa-camera" style="font-size:3rem;"></i>
                    <p>Nenhuma publicação encontrada</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>