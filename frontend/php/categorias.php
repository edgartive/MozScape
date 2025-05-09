<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/UploadController.php';
require_once __DIR__ . '/../../backend/app/controller/UsuarioController.php';

$db = (new Database())->getConnection();
$uploadController = new UploadController($db);
$usuarioController = new UsuarioController($db);

// Verifica se há pesquisa
$termo_pesquisa = $_GET['pesquisa'] ?? '';
$categoria_pesquisa = $_GET['categoria'] ?? '';

// Busca uploads com filtros
if (!empty($termo_pesquisa)) {
    if ($categoria_pesquisa === 'fotografo') {
        $uploads = $uploadController->pesquisarPorFotografo($termo_pesquisa);
    } else {
        $uploads = $uploadController->pesquisarUploads($termo_pesquisa);
    }
} else {
    $uploads = $uploadController->listarUploads();
}

// Verifica se usuário está logado
$usuario_logado = null;
$uploader = 0;
if (isset($_SESSION['id_usuario'])) {
    $usuario_logado = $usuarioController->buscarUsuarioPorId($_SESSION['id_usuario']);
    $uploader = $usuario_logado['uploader'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria Pública - Mozscape</title>
    <link rel="icon" href="../images/sea.png" type="image/png">
    <link rel="stylesheet" href="../css/categorias.css">
    <link rel="stylesheet" href="../fontes/webfonts/css/all.css">
    <style>
        .galeria-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .upload-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .upload-card:hover {
            transform: translateY(-5px);
        }

        .upload-imagem {
            width: 100%;
            height: 250px;
            object-fit: cover;
            cursor: pointer;
        }

        .upload-info {
            padding: 15px;
        }

        .upload-autor {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .upload-likes {
            color: #666;
            display: flex;
            align-items: center;
        }

        .upload-likes i {
            margin-right: 5px;
            color: #ff4757;
        }

        .barra-pesquisa {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .pesquisa-container {
            display: flex;
            gap: 10px;
        }

        #categoria {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        #pesquisa {
            flex-grow: 1;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .upload-btn {
            background: #3498db;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        @media (max-width: 900px) {
            .galeria-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .galeria-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header>
        <h2 style="text-align: center;">Galeria Pública</h2>
        <a href="index.php">
            <div id="back-home" style="position: absolute; top: 10px; left: 10px;
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 5px 10px;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);">
                <img src="../images/logo-black.png" alt="">
            </div>
        </a>

        <?php if (isset($_SESSION['id_usuario'])): ?>
            <div class="perfil-usuario">
                <div class="foto-perfil" style="background-image: url('../uploads/perfil/<?= $usuario_logado['foto_de_perfil_url'] ?? 'default.jpg' ?>');"></div>
                <select id="opcoes" name="categoria">
                    <option value=""></option>
                    <option value="visaoPerfil.php">Meu perfil</option>
                    <option value="favoritosECollections.php">Favoritos</option>
                    <option value="definicoes.php">Definições</option>
                    <option value="statusUpload.php">Ver pedidos</option>
                    <option value="ajuda_faq.php">Ajuda</option>
                    <option value="contacto.php">Contactos</option>
                    <option value="manual.php">Manual de instruções</option>
                    <option value="logout.php">Sair</option>
                </select>
            </div>
        <?php endif; ?>

        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="categorias.php" id="destacar">Explorar</a></li>
                <li><a href="story.php">Story</a></li>
                <li><a href="hallFama.php">Hall da fama</a></li>
            </ul>
        </nav>
    </header>

    <!-- Barra de Pesquisa -->
    <section class="barra-pesquisa">
        <form method="GET" action="">
            <div class="pesquisa-container">
                <select id="categoria" name="categoria">
                    <option value="tudo">Tudo</option>
                    <option value="fotografo" <?= ($categoria_pesquisa === 'fotografo') ? 'selected' : '' ?>>Fotógrafo</option>
                    <option value="natureza" <?= ($categoria_pesquisa === 'natureza') ? 'selected' : '' ?>>Natureza</option>
                    <option value="retrato" <?= ($categoria_pesquisa === 'retrato') ? 'selected' : '' ?>>Retrato</option>
                    <option value="paisagem" <?= ($categoria_pesquisa === 'paisagem') ? 'selected' : '' ?>>Paisagem</option>
                    <option value="céu" <?= ($categoria_pesquisa === 'céu') ? 'selected' : '' ?>>Céu</option>
                    <option value="carros" <?= ($categoria_pesquisa === 'carros') ? 'selected' : '' ?>>Carros</option>
                    <option value="outros" <?= ($categoria_pesquisa === 'outros') ? 'selected' : '' ?>>Outros</option>
                </select>

                <input type="text" id="pesquisa" name="pesquisa" placeholder="Pesquisar..." value="<?= htmlspecialchars($termo_pesquisa) ?>"> <button type="submit">
                    <i class="fas fa-search"></i>
                </button>

                <?php if ($uploader == 1): ?>
                    <a href="upload.php" class="upload-btn">
                        <i class="fas fa-upload"></i> Upload
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </section>
    <!-- Galeria de Uploads -->
    <section class="galeria-container">
        <?php
        $ids_exibidos = []; // Para evitar duplicações

        foreach ($uploads as $upload):
            // Evita duplicações
            if (in_array($upload['id_upload'], $ids_exibidos)) continue;
            $ids_exibidos[] = $upload['id_upload'];

            $autor = $usuarioController->buscarUsuarioPorId($upload['id_usuario']);
            $caminho_foto = '../../uploads/aprovados/' . $upload['foto_url'];
            $caminho_absoluto = $_SERVER['DOCUMENT_ROOT'] . '/uploads/aprovados/' . $upload['foto_url'];
        ?>
            <div class="upload-card">
                <?php if (file_exists($caminho_absoluto)): ?>
                    <img src="<?= $caminho_foto ?>"
                        alt="<?= htmlspecialchars($upload['descricao']) ?>"
                        class="upload-imagem"
                        loading="lazy">
                <?php else: ?>
                    <div class="imagem-placeholder">
                        <i class="fas fa-image"></i>
                        <span>Foto não disponível</span>
                    </div>
                <?php endif; ?>

                <div class="upload-info">
                    <div class="upload-meta">
                        <span class="upload-autor">
                            <i class="fas fa-user"></i>
                            <?= htmlspecialchars($autor['nome_completo'] ?? 'Desconhecido') ?>
                        </span>

                        <span class="upload-categoria">
                            <i class="fas fa-tag"></i>
                            <?= ucfirst(htmlspecialchars($upload['tipo'])) ?>
                        </span>
                    </div>

                    <div class="upload-likes">
                        <i class="fas fa-heart"></i>
                        <?= htmlspecialchars($upload['likes'] ?? 0) ?> curtidas
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($uploads)): ?>
            <div class="sem-uploads">
                <i class="fas fa-camera"></i>
                <p>Nenhuma foto disponível na galeria</p>
            </div>
        <?php endif; ?>
    </section>
    <!-- Rodapé -->
    <footer>
        <p>&copy; 2024 - Mozscape</p>
    </footer>

    <script>
        // Função para redirecionar com base na opção selecionada
        document.getElementById('opcoes')?.addEventListener('change', function() {
            const selectedValue = this.value;
            if (selectedValue !== "") {
                window.location.href = selectedValue;
            }
        });
    </script>
</body>

</html>