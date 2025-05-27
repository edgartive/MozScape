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

// Configuração da paginação
$fotos_por_pagina = 9;
$pagina_atual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina_atual - 1) * $fotos_por_pagina;

// Verifica se há pesquisa
$termo_pesquisa = $_GET['pesquisa'] ?? '';
$categoria_pesquisa = $_GET['categoria'] ?? '';

// Função para contar total de uploads
function contarUploads($uploadController, $termo_pesquisa, $categoria_pesquisa)
{
    if (!empty($termo_pesquisa)) {
        if ($categoria_pesquisa === 'fotografo') {
            return $uploadController->contarPorFotografo($termo_pesquisa);
        } elseif (in_array($categoria_pesquisa, ['natureza', 'retrato', 'paisagem', 'céu', 'carros', 'outros'])) {
            return $uploadController->contarPorCategoria($categoria_pesquisa, $termo_pesquisa);
        } else {
            return $uploadController->contarUploads($termo_pesquisa);
        }
    } else {
        if (in_array($categoria_pesquisa, ['natureza', 'retrato', 'paisagem', 'céu', 'carros', 'outros'])) {
            return $uploadController->contarUploadsPorCategoria($categoria_pesquisa);
        } else {
            return $uploadController->contarTodosUploads();
        }
    }
}

// Conta total de uploads
$total_uploads = contarUploads($uploadController, $termo_pesquisa, $categoria_pesquisa);
$total_paginas = ceil($total_uploads / $fotos_por_pagina);

// Busca uploads com paginação
if (!empty($termo_pesquisa)) {
    if ($categoria_pesquisa === 'fotografo') {
        $uploads = $uploadController->pesquisarPorFotografoComPaginacao($termo_pesquisa, $offset, $fotos_por_pagina);
    } elseif (in_array($categoria_pesquisa, ['natureza', 'retrato', 'paisagem', 'céu', 'carros', 'outros'])) {
        $uploads = $uploadController->pesquisarPorCategoriaComPaginacao($categoria_pesquisa, $termo_pesquisa, $offset, $fotos_por_pagina);
    } else {
        $uploads = $uploadController->pesquisarUploadsComPaginacao($termo_pesquisa, $offset, $fotos_por_pagina);
    }
} else {
    if (in_array($categoria_pesquisa, ['natureza', 'retrato', 'paisagem', 'céu', 'carros', 'outros'])) {
        $uploads = $uploadController->listarUploadsPorCategoriaComPaginacao($categoria_pesquisa, $offset, $fotos_por_pagina);
    } else {
        $uploads = $uploadController->listarUploadsComPaginacao($offset, $fotos_por_pagina);
    }
}

// AJAX para pesquisa dinâmica com paginação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    $termo_pesquisa = $_POST['pesquisa'] ?? '';
    $categoria_pesquisa = $_POST['categoria'] ?? '';
    $pagina_atual = isset($_POST['pagina']) ? max(1, intval($_POST['pagina'])) : 1;
    $offset = ($pagina_atual - 1) * $fotos_por_pagina;

    // Conta total de uploads para a pesquisa
    $total_uploads = contarUploads($uploadController, $termo_pesquisa, $categoria_pesquisa);
    $total_paginas = ceil($total_uploads / $fotos_por_pagina);

    // Busca uploads com filtros e paginação
    if (!empty($termo_pesquisa)) {
        if ($categoria_pesquisa === 'fotografo') {
            $uploads = $uploadController->pesquisarPorFotografoComPaginacao($termo_pesquisa, $offset, $fotos_por_pagina);
        } elseif (in_array($categoria_pesquisa, ['natureza', 'retrato', 'paisagem', 'céu', 'carros', 'outros'])) {
            $uploads = $uploadController->pesquisarPorCategoriaComPaginacao($categoria_pesquisa, $termo_pesquisa, $offset, $fotos_por_pagina);
        } else {
            $uploads = $uploadController->pesquisarUploadsComPaginacao($termo_pesquisa, $offset, $fotos_por_pagina);
        }
    } else {
        if (in_array($categoria_pesquisa, ['natureza', 'retrato', 'paisagem', 'céu', 'carros', 'outros'])) {
            $uploads = $uploadController->listarUploadsPorCategoriaComPaginacao($categoria_pesquisa, $offset, $fotos_por_pagina);
        } else {
            $uploads = $uploadController->listarUploadsComPaginacao($offset, $fotos_por_pagina);
        }
    }

    $result = [
        'uploads' => [],
        'paginacao' => [
            'pagina_atual' => $pagina_atual,
            'total_paginas' => $total_paginas,
            'total_uploads' => $total_uploads
        ]
    ];

    foreach ($uploads as $upload) {
        $autor = $usuarioController->buscarUsuarioPorId($upload['id_usuario']);
        $result['uploads'][] = [
            'id_upload' => $upload['id_upload'],
            'foto_url' => $upload['foto_url'],
            'descricao' => $upload['descricao'],
            'likes' => $upload['likes'],
            'autor' => $autor['nome_completo'] ?? 'Desconhecido'
        ];
    }
    echo json_encode($result);
    exit;
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
            min-height: 600px;
        }

        .upload-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            cursor: pointer;
        }

        .upload-card:hover {
            transform: translateY(-5px);
        }

        .upload-imagem {
            width: 100%;
            height: 250px;
            object-fit: cover;
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

        .imagem-placeholder {
            height: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            color: #666;
        }

        .imagem-placeholder i {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .sem-uploads {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .sem-uploads i {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        /* Estilos da Paginação */
        .paginacao-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 30px 0;
            gap: 15px;
        }

        .btn-paginacao {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-paginacao:hover:not(:disabled) {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-paginacao:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
            transform: none;
        }

        .info-paginacao {
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 5px;
            color: #495057;
            font-weight: 500;
            border: 1px solid #dee2e6;
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

        @media (max-width: 900px) {
            .galeria-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .galeria-container {
                grid-template-columns: 1fr;
            }

            .paginacao-container {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <header>
        <h3></h3>
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

                <input type="text" id="pesquisa" name="pesquisa" placeholder="Pesquisar..." value="<?= htmlspecialchars($termo_pesquisa) ?>">
                <button type="submit">
                    <i class="fas fa-search"></i>
                </button>

                <?php if ($uploader == 1): ?>
                    <a href="upload.php" class="upload-btn">
                        <i class="fas fa-upload"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <!-- Galeria de Uploads -->
    <section class="galeria-container">
        <?php
        $ids_exibidos = [];
        foreach ($uploads as $upload):
            if (in_array($upload['id_upload'], $ids_exibidos)) continue;
            $ids_exibidos[] = $upload['id_upload'];

            $autor = $usuarioController->buscarUsuarioPorId($upload['id_usuario']);
            $caminho_foto = '../uploads/aprovado/' . $upload['foto_url'];
            $caminho_absoluto = $_SERVER['DOCUMENT_ROOT'] . '/MozScape/frontend/uploads/aprovado/' . $upload['foto_url'];
        ?>
            <div class="upload-card" onclick="window.location.href='detalhes_upload.php?id=<?= $upload['id_upload'] ?>'">
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
                    <div class="upload-autor" style="color: black;">
                        <?= htmlspecialchars($autor['nome_completo'] ?? 'Desconhecido') ?>
                    </div>
                    <b>
                        <p style="color: #3498db;"><?= htmlspecialchars($upload['descricao']) ?></p>
                    </b>
                    <div style="color: black;" class="upload-likes">
                        <i class="fas fa-heart"></i>
                        <?= $upload['likes'] ?> likes
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($uploads)): ?>
            <div class="sem-uploads">
                <i class="fas fa-camera"></i>
                <p>Nenhuma foto encontrada na galeria.</p>
            </div>
        <?php endif; ?>
    </section>

    <!-- Paginação -->
    <div class="paginacao-container">
        <button class="btn-paginacao" id="btn-anterior" <?= ($pagina_atual <= 1) ? 'disabled' : '' ?>>
            <i class="fas fa-chevron-left"></i>
            Anterior
        </button>

        <div class="info-paginacao">
            Página <span id="pagina-atual"><?= $pagina_atual ?></span> de <span id="total-paginas"><?= $total_paginas ?></span>
        </div>

        <button class="btn-paginacao" id="btn-proximo" <?= ($pagina_atual >= $total_paginas) ? 'disabled' : '' ?>>
            Próximo
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <!-- Rodapé -->
    <footer>
        <p>&copy; 2024 - Mozscape</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pesquisaInput = document.getElementById('pesquisa');
            const categoriaSelect = document.getElementById('categoria');
            const galeria = document.querySelector('.galeria-container');
            const btnAnterior = document.getElementById('btn-anterior');
            const btnProximo = document.getElementById('btn-proximo');
            const paginaAtualSpan = document.getElementById('pagina-atual');
            const totalPaginasSpan = document.getElementById('total-paginas');

            let paginaAtual = <?= $pagina_atual ?>;
            let totalPaginas = <?= $total_paginas ?>;
            let timeout = null;

            function renderUploads(uploads) {
                galeria.innerHTML = '';
                if (uploads.length === 0) {
                    galeria.innerHTML = `<div class="sem-uploads">
                        <i class="fas fa-camera"></i>
                        <p>Nenhuma foto encontrada na galeria.</p>
                    </div>`;
                    return;
                }

                uploads.forEach(upload => {
                    const card = document.createElement('div');
                    card.className = 'upload-card';
                    card.onclick = () => window.location.href = `detalhes_upload.php?id=${upload.id_upload}`;

                    let imgPath = '../uploads/aprovado/' + upload.foto_url;
                    let img = `<img src="${imgPath}" alt="${upload.descricao}" class="upload-imagem" loading="lazy">`;

                    if (!upload.foto_url) {
                        img = `<div class="imagem-placeholder">
                            <i class="fas fa-image"></i>
                            <span>Foto não disponível</span>
                        </div>`;
                    }

                    card.innerHTML = `
                        ${img}
                        <div class="upload-info">
                            <div class="upload-autor" style="color: black;">
                                ${upload.autor}
                            </div>
                            <b>
                                <p style="color: #3498db;">${upload.descricao}</p>
                            </b>
                            <div style="color: black;" class="upload-likes">
                                <i class="fas fa-heart"></i>
                                ${upload.likes} likes
                            </div>
                        </div>
                    `;
                    galeria.appendChild(card);
                });
            }

            function atualizarPaginacao(paginacao) {
                paginaAtual = paginacao.pagina_atual;
                totalPaginas = paginacao.total_paginas;

                paginaAtualSpan.textContent = paginaAtual;
                totalPaginasSpan.textContent = totalPaginas;

                btnAnterior.disabled = paginaAtual <= 1;
                btnProximo.disabled = paginaAtual >= totalPaginas;
            }

            function fetchUploads(resetarPagina = false) {
                if (resetarPagina) {
                    paginaAtual = 1;
                }

                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const formData = new FormData();
                    formData.append('ajax', '1');
                    formData.append('pesquisa', pesquisaInput.value);
                    formData.append('categoria', categoriaSelect.value);
                    formData.append('pagina', paginaAtual);

                    galeria.innerHTML = `<div class="sem-uploads"><i class="fas fa-spinner fa-spin"></i></div>`;

                    fetch(window.location.pathname, {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            renderUploads(data.uploads);
                            atualizarPaginacao(data.paginacao);
                        })
                        .catch(() => {
                            galeria.innerHTML = `<div class="sem-uploads"><i class="fas fa-exclamation-triangle"></i> Erro ao carregar resultados.</div>`;
                        });
                }, 300);
            }

            // Event listeners para pesquisa
            pesquisaInput.addEventListener('input', () => fetchUploads(true));
            categoriaSelect.addEventListener('change', () => fetchUploads(true));

            // Event listeners para paginação
            btnAnterior.addEventListener('click', () => {
                if (paginaAtual > 1) {
                    paginaAtual--;
                    fetchUploads();
                }
            });

            btnProximo.addEventListener('click', () => {
                if (paginaAtual < totalPaginas) {
                    paginaAtual++;
                    fetchUploads();
                }
            });

            // Função para redirecionar com base na opção selecionada
            document.getElementById('opcoes')?.addEventListener('change', function() {
                const selectedValue = this.value;
                if (selectedValue !== "") {
                    window.location.href = selectedValue;
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Criar indicador de progresso
            const progressIndicator = document.createElement('div');
            progressIndicator.className = 'progress-indicator';
            progressIndicator.innerHTML = '<div class="progress-bar"></div>';
            document.body.appendChild(progressIndicator);

            // Criar botão voltar ao topo
            const voltarTopo = document.createElement('button');
            voltarTopo.className = 'voltar-topo';
            voltarTopo.innerHTML = '<i class="fas fa-chevron-up"></i>';
            voltarTopo.title = 'Voltar ao topo';
            document.body.appendChild(voltarTopo);

            // Criar contador de resultados
            const contadorResultados = document.createElement('div');
            contadorResultados.className = 'contador-resultados';
            const galeriaContainer = document.querySelector('.galeria-container');
            galeriaContainer.parentNode.insertBefore(contadorResultados, galeriaContainer);

            const progressBar = document.querySelector('.progress-bar');

            // Função para atualizar indicador de progresso
            function atualizarProgresso(paginaAtual, totalPaginas) {
                const progresso = (paginaAtual / totalPaginas) * 100;
                progressBar.style.width = progresso + '%';
            }

            // Função para mostrar/ocultar botão voltar ao topo
            function toggleVoltarTopo() {
                if (window.scrollY > 300) {
                    voltarTopo.classList.add('visible');
                } else {
                    voltarTopo.classList.remove('visible');
                }
            }

            // Event listener para scroll
            window.addEventListener('scroll', toggleVoltarTopo);

            // Event listener para voltar ao topo
            voltarTopo.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Função para atualizar contador de resultados
            function atualizarContador(paginaAtual, totalPaginas, totalUploads) {
                const inicio = ((paginaAtual - 1) * 9) + 1;
                const fim = Math.min(paginaAtual * 9, totalUploads);

                if (totalUploads > 0) {
                    contadorResultados.innerHTML = `
                Mostrando <strong>${inicio}-${fim}</strong> de <strong>${totalUploads}</strong> resultados
            `;
                } else {
                    contadorResultados.innerHTML = '';
                }
            }

            // Função para adicionar efeito de loading
            function mostrarLoading() {
                galeriaContainer.classList.add('loading');
                galeriaContainer.innerHTML = `
            <div class="galeria-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Carregando fotos...</p>
            </div>
        `;
            }

            // Função para remover efeito de loading
            function ocultarLoading() {
                galeriaContainer.classList.remove('loading');
            }

            // Função para scroll suave para a galeria
            function scrollParaGaleria() {
                const galeriaTop = galeriaContainer.offsetTop - 20;
                window.scrollTo({
                    top: galeriaTop,
                    behavior: 'smooth'
                });
            }

            // Função para adicionar animações staggered aos cards
            function adicionarAnimacoes() {
                const cards = document.querySelectorAll('.upload-card');
                cards.forEach((card, index) => {
                    card.style.animationDelay = (index * 0.1) + 's';
                });
            }

            // Navegação por teclado
            document.addEventListener('keydown', function(e) {
                const btnAnterior = document.getElementById('btn-anterior');
                const btnProximo = document.getElementById('btn-proximo');

                // Seta esquerda - página anterior
                if (e.key === 'ArrowLeft' && !btnAnterior.disabled) {
                    e.preventDefault();
                    btnAnterior.click();
                }

                // Seta direita - próxima página
                if (e.key === 'ArrowRight' && !btnProximo.disabled) {
                    e.preventDefault();
                    btnProximo.click();
                }

                // Home - primeira página
                if (e.key === 'Home' && e.ctrlKey) {
                    e.preventDefault();
                    if (!btnAnterior.disabled) {
                        // Simular clique na primeira página
                        const event = new CustomEvent('irParaPrimeiraPagina');
                        document.dispatchEvent(event);
                    }
                }

                // End - última página
                if (e.key === 'End' && e.ctrlKey) {
                    e.preventDefault();
                    if (!btnProximo.disabled) {
                        // Simular clique na última página
                        const event = new CustomEvent('irParaUltimaPagina');
                        document.dispatchEvent(event);
                    }
                }
            });

            // Salvar estado na URL
            function atualizarURL(pagina, pesquisa, categoria) {
                const url = new URL(window.location);
                url.searchParams.set('pagina', pagina);
                if (pesquisa) {
                    url.searchParams.set('pesquisa', pesquisa);
                } else {
                    url.searchParams.delete('pesquisa');
                }
                if (categoria && categoria !== 'tudo') {
                    url.searchParams.set('categoria', categoria);
                } else {
                    url.searchParams.delete('categoria');
                }

                window.history.pushState({}, '', url);
            }

            // Função para vibração em dispositivos móveis (se suportado)
            function vibrar() {
                if ('vibrate' in navigator) {
                    navigator.vibrate(50);
                }
            }

            // Melhorar a função fetchUploads original
            const fetchUploadsOriginal = window.fetchUploads;
            window.fetchUploads = function(resetarPagina = false) {
                mostrarLoading();
                vibrar();

                if (resetarPagina) {
                    window.paginaAtual = 1;
                }

                const pesquisaInput = document.getElementById('pesquisa');
                const categoriaSelect = document.getElementById('categoria');

                // Atualizar URL
                atualizarURL(window.paginaAtual, pesquisaInput.value, categoriaSelect.value);

                clearTimeout(window.timeout);
                window.timeout = setTimeout(() => {
                    const formData = new FormData();
                    formData.append('ajax', '1');
                    formData.append('pesquisa', pesquisaInput.value);
                    formData.append('categoria', categoriaSelect.value);
                    formData.append('pagina', window.paginaAtual);

                    fetch(window.location.pathname, {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            ocultarLoading();
                            window.renderUploads(data.uploads);
                            window.atualizarPaginacao(data.paginacao);

                            // Atualizar indicadores
                            atualizarProgresso(data.paginacao.pagina_atual, data.paginacao.total_paginas);
                            atualizarContador(data.paginacao.pagina_atual, data.paginacao.total_paginas, data.paginacao.total_uploads);

                            // Adicionar animações
                            setTimeout(adicionarAnimacoes, 100);

                            // Scroll para galeria se não for a primeira página
                            if (window.paginaAtual > 1 && !resetarPagina) {
                                setTimeout(scrollParaGaleria, 200);
                            }
                        })
                        .catch(error => {
                            ocultarLoading();
                            galeriaContainer.innerHTML = `
                    <div class="sem-uploads">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Erro ao carregar resultados. Tente novamente.
                    </div>
                `;
                            console.error('Erro:', error);
                        });
                }, 300);
            };

            // Inicializar contadores e indicadores
            const paginaAtual = parseInt(document.getElementById('pagina-atual').textContent);
            const totalPaginas = parseInt(document.getElementById('total-paginas').textContent);

            // Calcular total de uploads baseado na página atual
            const uploadsNaPagina = document.querySelectorAll('.upload-card').length;
            const totalUploads = ((paginaAtual - 1) * 9) + uploadsNaPagina;

            atualizarProgresso(paginaAtual, totalPaginas);
            atualizarContador(paginaAtual, totalPaginas, totalUploads);

            // Adicionar animações iniciais
            setTimeout(adicionarAnimacoes, 100);

            // Detectar mudanças na URL (botão voltar do navegador)
            window.addEventListener('popstate', function() {
                location.reload();
            });

            // Adicionar tooltips aos botões
            document.getElementById('btn-anterior').title = 'Página anterior (←)';
            document.getElementById('btn-proximo').title = 'Próxima página (→)';

            console.log('Sistema de paginação carregado com sucesso!');
        });
    </script>
</body>

</html>