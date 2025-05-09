<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id'])) {
    header("Location: galeria_publica.php");
    exit;
}

require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/UploadController.php';
require_once __DIR__ . '/../../backend/app/controller/UsuarioController.php';

$db = (new Database())->getConnection();
$uploadController = new UploadController($db);
$usuarioController = new UsuarioController($db);

$id_upload = $_GET['id'];
$upload = $uploadController->buscarUploadPorId($id_upload);

if (!$upload) {
    header("Location: galeria_publica.php");
    exit;
}

$autor = $usuarioController->buscarUsuarioPorId($upload['usuario_id']);

// Verifica se usuário está logado
$usuario_logado = null;
if (isset($_SESSION['id_usuario'])) {
    $usuario_logado = $usuarioController->buscarUsuarioPorId($_SESSION['id_usuario']);
}

// Processar curtida
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['curtir'])) {
    $uploadController->curtirUpload($id_upload);
    $upload = $uploadController->buscarUploadPorId($id_upload); // Atualiza dados
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($upload['descricao']) ?> - Mozscape</title>
    <link rel="stylesheet" href="../css/categorias.css">
    <link rel="stylesheet" href="../fontes/webfonts/css/all.css">
    <style>
        .detalhes-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .upload-imagem {
            width: 100%;
            max-height: 600px;
            object-fit: contain;
            border-radius: 8px;
        }

        .upload-info {
            margin-top: 20px;
        }

        .upload-autor {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .autor-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .upload-stats {
            display: flex;
            gap: 20px;
            margin: 15px 0;
        }

        .like-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            color: #ff4757;
        }

        .upload-descricao {
            margin: 20px 0;
            line-height: 1.6;
        }

        .upload-data {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <header>
        <a href="galeria_publica.php">
            <div id="back-home" style="position: absolute; top: 10px; left: 10px;
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 5px 10px;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);">
                <i class="fas fa-arrow-left"></i>
            </div>
        </a>

        <h2 style="text-align: center;">Detalhes da Foto</h2>
    </header>

    <div class="detalhes-container">
        <img src="../uploads/<?= htmlspecialchars($upload['foto_url']) ?>" alt="<?= htmlspecialchars($upload['descricao']) ?>" class="upload-imagem">

        <div class="upload-info">
            <div class="upload-autor">
                <img src="../uploads/perfil/<?= htmlspecialchars($autor['foto_de_perfil_url'] ?? 'default.jpg') ?>" class="autor-avatar">
                <div>
                    <h3><?= htmlspecialchars($autor['nome_completo'] ?? 'Autor desconhecido') ?></h3>
                    <p class="upload-data">Postado em: <?= date('d/m/Y H:i', strtotime($upload['data_upload'])) ?></p>
                </div>
            </div>

            <div class="upload-stats">
                <form method="POST">
                    <button type="submit" name="curtir" class="like-btn">
                        <i class="fas fa-heart"></i> <?= $upload['likes'] ?> Curtidas
                    </button>
                </form>
                <span><i class="fas fa-tag"></i> <?= ucfirst($upload['tipo']) ?></span>
            </div>

            <div class="upload-descricao">
                <h3>Descrição</h3>
                <p><?= nl2br(htmlspecialchars($upload['descricao'])) ?></p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 - Mozscape</p>
    </footer>
</body>

</html>