<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/UploadController.php';
require_once __DIR__ . '/../../backend/app/controller/UsuarioController.php';

// Estabelece conexão com o banco de dados
$database = new Database();
$db = $database->getConnection();

// Inicializa controllers
$uploadController = new UploadController($db);
$usuarioController = new UsuarioController($db);

// Valida e obtém o ID do upload
$id_upload = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_upload) {
    header("Location: galeria.php");
    exit;
}

// Busca informações do upload
$upload = $uploadController->buscarUploadPorId($id_upload);
if (!$upload) {
    header("Location: galeria.php");
    exit;
}

// Busca informações do autor
$autor = $usuarioController->buscarUsuarioPorId($upload['id_usuario']);
// Gera o caminho da foto usando o user id e o nome da foto
$caminho_foto = '/backend/uploads/aprovados/' . $upload['id_usuario'] . '/' . $upload['foto_url'];
$caminho_absoluto = $_SERVER['DOCUMENT_ROOT'] . $caminho_foto;

// Verifica sessão e likes
$isLoggedIn = isset($_SESSION['id_usuario']);
$usuarioId = $isLoggedIn ? $_SESSION['id_usuario'] : null;
$jaCurtiu = $isLoggedIn ? $uploadController->usuarioJaCurtiu($id_upload, $usuarioId) : false;
// Processar like
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'like') {
    header('Content-Type: application/json');
    require_once __DIR__ . '/../../backend/app/controller/UploadController.php';
    $db = (new Database())->getConnection();
    $controller = new UploadController($db);

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $usuarioId = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

    if (!$usuarioId) {
        echo json_encode(['success' => false, 'message' => 'Você precisa estar logado para curtir.']);
        exit;
    }

    $result = $controller->curtirUpload($id, $usuarioId);

    if ($result) {
        // Buscar o novo número de likes
        $upload = $controller->buscarUploadPorId($id);
        $likes = $upload ? $upload['likes'] : 0;
        echo json_encode(['success' => true, 'likes' => $likes]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao curtir.']);
    }
    exit;
}
// Processa download se for POST

// Atualiza a variável $jaCurtiu após processar o like via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'like') {
    // O código do like já está acima, então não precisa repetir aqui.
    // Apenas retorna após processar o like.
    exit;
}

// Atualiza $upload e $jaCurtiu após possível like
if ($isLoggedIn) {
    $upload = $uploadController->buscarUploadPorId($id_upload);
    $jaCurtiu = $uploadController->usuarioJaCurtiu($id_upload, $usuarioId);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'download') {
    if (!$isLoggedIn) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Você precisa estar logado para baixar.']);
        exit;
    }
    if (file_exists($caminho_absoluto)) {
        // Força o download do arquivo
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($caminho_absoluto) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($caminho_absoluto));
        readfile($caminho_absoluto);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Arquivo não encontrado.']);
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download']) && $isLoggedIn) {
    if (file_exists($caminho_absoluto)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($caminho_absoluto) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($caminho_absoluto));
        readfile($caminho_absoluto);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($upload['descricao']) ?> - Mozscape</title>
    <link rel="icon" href="../images/sea.png" type="image/png">
    <link rel="stylesheet" href="../fontes/webfonts/css/all.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #0a0f25;
            color: #fff;
        }

        .detalhes-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 20px;
            background-color: #1a2035;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .foto-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .btn-like.disabled {
            background-color: #3498db !important;
            cursor: not-allowed;
        }

        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .foto-detalhes {
            max-width: 100%;
            max-height: 70vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .info-container {
            padding: 20px;
        }

        .autor-info {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .foto-perfil {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-size: cover;
            margin-right: 15px;
            border: 3px solid #3498db;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .dados-foto {
            margin: 25px 0;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }

        .dados-foto p {
            margin: 12px 0;
            font-size: 1.1rem;
        }

        .dados-foto strong {
            color: #3498db;
        }

        .acoes-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-acao {
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            font-weight: bold;
            transition: all 0.3s;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-voltar {
            background: #2c3e50;
            color: white;
        }

        .btn-voltar:hover {
            background: #34495e;
        }

        .btn-like {
            background: <?= $jaCurtiu ? '#3498db' : '#ff4757' ?>;
            color: white;
        }

        .btn-like:hover {
            background: <?= $jaCurtiu ? '#2980b9' : '#e84118' ?>;
        }

        .btn-download {
            background: #2ecc71;
            color: white;
        }

        .btn-download:hover {
            background: #27ae60;
        }

        .imagem-placeholder {
            height: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            color: #aaa;
            border-radius: 10px;
            border: 2px dashed #444;
        }

        .imagem-placeholder i {
            font-size: 4rem;
            margin-bottom: 15px;
            color: #555;
        }

        @media (max-width: 768px) {
            .detalhes-container {
                margin: 0;
                border-radius: 0;
                padding: 15px;
            }

            .foto-detalhes {
                max-height: 50vh;
            }

            .acoes-container {
                flex-direction: column;
            }

            .btn-acao {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="detalhes-container">
        <div class="foto-container">
            <?php
            $caminho_foto = '../uploads/aprovado/' . $upload['foto_url'];
            $caminho_absoluto = $_SERVER['DOCUMENT_ROOT'] . '/MozScape/frontend/uploads/aprovado/' . $upload['foto_url'];
            ?>
            <?php if (file_exists($caminho_absoluto)): ?>
                <img src="<?= $caminho_foto ?>"
                    alt="<?= htmlspecialchars($upload['descricao']) ?>"
                    class="foto-detalhes">
            <?php else: ?>
                <div class="imagem-placeholder">
                    <i class="fas fa-image"></i>
                    <span>Foto não disponível</span>
                </div>
            <?php endif; ?>
        </div>

        <div class="info-container">
            <div class="autor-info">
                <div class="foto-perfil"
                    style="background-image: url('../uploads/perfil/<?= htmlspecialchars($autor['foto_de_perfil_url'] ?? 'default.jpg') ?>')">
                </div>
                <div>
                    <h2 style="margin: 0; color: #3498db;">
                        <a href="perfilpublicodeuploader.php?id=<?= urlencode($autor['id_usuario']) ?>" style="color: #3498db; text-decoration: underline;">
                            <?= htmlspecialchars($autor['nome_completo'] ?? 'Desconhecido') ?>
                        </a>
                    </h2>
                    <small style="color: #aaa;"><?= date('d/m/Y H:i', strtotime($upload['data_upload'])) ?></small>
                </div>
            </div>

            <form method="post" id="likeForm" style="display: none;">
                <input type="hidden" name="like" value="1">
            </form>

            <form method="post" id="downloadForm" style="display: none;">
                <input type="hidden" name="download" value="1">
            </form>

            <div class="dados-foto">
                <p><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($upload['descricao'])) ?></p>
                <p><strong>Categoria:</strong> <?= ucfirst(htmlspecialchars($upload['tipo'])) ?></p>
                <p class="upload-likes"><i class="fas fa-heart"></i> <?= htmlspecialchars($upload['likes']) ?> likes</p>
            </div>

            <div class="acoes-container">
                <button class="btn-acao btn-voltar" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Voltar
                </button>
                <button class="btn-acao btn-like" id="btnLike" <?= $jaCurtiu ? 'disabled' : '' ?>>
                    <i class="fas fa-heart"></i> <?= $jaCurtiu ? 'Você curtiu' : 'Curtir' ?>
                </button>
                <button class="btn-acao btn-download" id="btnDownload">
                    <i class="fas fa-download"></i> Baixar
                </button>
            </div>
        </div>
    </div>
    <script>
        // Corrige o caminho da foto do perfil e da foto do post caso estejam quebrados
        document.addEventListener('DOMContentLoaded', function() {
            // Foto do perfil
            const perfilDiv = document.querySelector('.foto-perfil');
            if (perfilDiv) {
                // Atualiza o caminho para a nova pasta
                let url = perfilDiv.style.backgroundImage.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
                // Se o caminho não começar com '../uploads/profile_pics/', corrige
                if (!url.includes('uploads/profile_pics/')) {
                    url = url.replace('uploads/perfil/', 'uploads/profile_pics/');
                    perfilDiv.style.backgroundImage = `url('${url}')`;
                }
                const img = new Image();
                img.onload = function() {
                    // ok
                };
                img.onerror = function() {
                    perfilDiv.style.backgroundImage = "url('../uploads/profile_pics/default.jpg')";
                };
                img.src = url;
            }

            // Foto do post
            const fotoDetalhes = document.querySelector('.foto-detalhes');
            if (fotoDetalhes) {
                fotoDetalhes.onerror = function() {
                    fotoDetalhes.style.display = 'none';
                    const placeholder = document.createElement('div');
                    placeholder.className = 'imagem-placeholder';
                    placeholder.innerHTML = '<i class="fas fa-image"></i><span>Foto não disponível</span>';
                    fotoDetalhes.parentNode.appendChild(placeholder);
                };
            }
        });
    </script>
    <script>
        document.getElementById('btnLike').addEventListener('click', async function(e) {
            e.preventDefault();
            const btn = this;
            const likesCounter = document.querySelector('.upload-likes');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                const response = await fetch('detalhes_upload.php?action=like&id=<?= $id_upload ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'like=true'
                });

                const data = await response.json();

                if (data.success) {
                    // Atualiza visualmente
                    if (likesCounter) {
                        likesCounter.innerHTML = `<i class="fas fa-heart"></i> ${data.likes} likes`;
                    }
                    btn.innerHTML = '<i class="fas fa-heart"></i> Curtido';
                    btn.style.backgroundColor = '#3498db';
                    btn.disabled = true;
                } else {
                    alert(data.message);
                    btn.disabled = data.message && data.message.includes('curtiu') ? true : false;
                    btn.innerHTML = '<i class="fas fa-heart"></i> Curtir';
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao curtir. Tente novamente.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-heart"></i> Curtir';
            }
        });
    </script>
</body>

</html>