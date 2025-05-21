<?php
session_start();
require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/UploadController.php';
require_once __DIR__ . '/../../backend/app/controller/UsuarioController.php';

// Inicializa conexão e controllers
$db = (new Database())->getConnection();
$uploadController = new UploadController($db);
$usuarioController = new UsuarioController($db);

// Busca os dados reais
$melhorFotografo = $uploadController->buscarFotografoComMaisUploads();
$fotoMaisCurtida = $uploadController->buscarFotoComMaisLikes();
$topFotografos = $uploadController->buscarTopFotografos(3);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall da Fama - Mozscape</title>
    <link rel="stylesheet" href="../css/hallFama.css">
    <link rel="stylesheet" href="../css/categorias.css">
    <link rel="stylesheet" href="../fontes/webfonts/css/all.css">

</head>
<style>
    :root {
        --cor-primaria: #3498db;
        --cor-secundaria: #2c3e50;
        --cor-texto: #34495e;
        --cor-fundo: #f8f9fa;
        --cor-card: #ffffff;
    }

    h1 {
        text-align: center;
        color: var(--cor-secundaria);
        margin: 30px 0;
    }

    .hall-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }

    .hall-card {
        background-color: var(--cor-card);
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .hall-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .hall-card h2 {
        color: var(--cor-primaria);
        margin-top: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .fotografo-info {
        display: flex;
        align-items: center;
        margin-top: 15px;
    }

    .fotografo-foto {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background-size: cover;
        background-position: center;
        border: 3px solid var(--cor-primaria);
        margin-right: 15px;
    }

    .fotografo-detalhes h3 {
        margin: 0;
        color: var(--cor-secundaria);
    }

    .fotografo-detalhes p {
        margin: 5px 0 0;
        color: var(--cor-texto);
    }

    .foto-preview {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        margin: 15px 0;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .stats {
        display: flex;
        justify-content: space-around;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .stat {
        text-align: center;
    }

    .stat .number {
        font-size: 1.5em;
        font-weight: bold;
        color: var(--cor-primaria);
    }

    .stat .label {
        font-size: 0.9em;
        color: var(--cor-texto);
    }

    .top-fotografos {
        margin-top: 20px;
    }

    .top-fotografo {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .top-fotografo:last-child {
        border-bottom: none;
    }

    .top-fotografo .posicao {
        font-weight: bold;
        color: var(--cor-primaria);
        margin-right: 15px;
        width: 25px;
        text-align: center;
    }

    .top-fotografo .foto {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-size: cover;
        background-position: center;
        margin-right: 15px;
    }

    .top-fotografo .nome {
        flex-grow: 1;
    }

    .top-fotografo .uploads {
        color: var(--cor-secundaria);
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .hall-grid {
            grid-template-columns: 1fr;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        nav ul {
            margin-top: 15px;
        }
    }
</style>

<body>
    <header>
        <h2 style="text-align: center;"> </h2>
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
        <div class="perfil-usuario">
            <div class="foto-perfil" style="background-image: url('../images/perfil.png');"></div>
            <select id="opcoes" name="categoria">
                <option value=""></option> <!-- Opção padrão -->
                <option value="visaoPerfil.php">Meu perfil</option>
                <option value="favoritosECollections.php">Favoritos</option>
                <option value="definicoes.php">Definições</option>
                <option value="statusUpload.php">Ver pedidos</option>
                <option value="ajuda_faq.php">Ajuda</option>
                <option value="contacto.php">Contactos</option>
                <option value="manual.php">Manual de instrunçoes </option>
                <option value="logout.php">Sair</option>
            </select>

            <script>
                // Função para redirecionar com base na opção selecionada
                document.getElementById('opcoes').addEventListener('change', function() {
                    const selectedValue = this.value; // Pega o valor da opção selecionada

                    if (selectedValue !== "") { // Ignora a opção padrão
                        window.location.href = selectedValue; // Redireciona para a página
                    }
                });
            </script>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="categorias.php">Explorar</a></li>
                <li><a href="story.php">Story</a></li>
                <li><a href="hallFama.php" id="destacar">Hall da fama</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h1>🏆 Hall da Fama</h1>

        <div class="hall-grid">
            <!-- Fotógrafo com mais uploads -->
            <div class="hall-card">
                <h2><i class="fas fa-crown"></i> Fotógrafo do Mês</h2>
                <?php if ($melhorFotografo): ?>
                    <div class="fotografo-info">
                        <div class="fotografo-foto" style="background-image: url('../uploads/perfil/<?= htmlspecialchars($melhorFotografo['foto_de_perfil_url'] ?? 'default.jpg') ?>');"></div>
                        <div class="fotografo-detalhes">
                            <h3><?= htmlspecialchars($melhorFotografo['nome_completo']) ?></h3>
                            <p>Membro desde <?= date('Y', strtotime($melhorFotografo['data_registro'] ?? 'now')) ?></p>
                        </div>
                    </div>
                    <div class="stats">
                        <div class="stat">
                            <div class="number"><?= $melhorFotografo['total_uploads'] ?></div>
                            <div class="label">Uploads</div>
                        </div>
                        <div class="stat">
                            <div class="number"><?= $melhorFotografo['total_likes'] ?? '0' ?></div>
                            <div class="label">Likes</div>
                        </div>
                        <div class="stat">
                            <div class="number"><?= $melhorFotografo['seguidores'] ?? '0' ?></div>
                            <div class="label">Seguidores</div>
                        </div>
                    </div>
                <?php else: ?>
                    <p>Nenhum fotógrafo encontrado</p>
                <?php endif; ?>
            </div>

            <!-- Foto com mais likes -->

            <div class="hall-card">
                <h2><i class="fas fa-heart"></i> Foto mais gostada</h2>
                <?php if ($fotoMaisCurtida): ?>
                    <img src="../uploads/aprovados/<?= htmlspecialchars($fotoMaisCurtida['foto_url']) ?>" alt="<?= htmlspecialchars($fotoMaisCurtida['descricao']) ?>" class="foto-preview">
                    <p style="color: #2c3e50;"><?= htmlspecialchars($fotoMaisCurtida['descricao']) ?></p>
                    <div class="stats">
                        <div class="stat">
                            <div class="number"><?= $fotoMaisCurtida['likes'] ?></div>
                            <div class="label">Likes</div>
                        </div>
                        <div class="stat">
                            <div class="number"><?= $fotoMaisCurtida['visualizacoes'] ?? '0' ?></div>
                            <div class="label">Visualizações</div>
                        </div>
                        <div class="stat">
                            <div class="number"><?= $fotoMaisCurtida['comentarios'] ?? '0' ?></div>
                            <div class="label">Comentários</div>
                        </div>
                    </div>
                    <p class="autor">Por: <?= htmlspecialchars($fotoMaisCurtida['autor_nome']) ?></p>
                <?php else: ?>
                    <p>Nenhuma foto encontrada</p>
                <?php endif; ?>
            </div>

            <!-- Foto mais baixada 
            <div class="hall-card">
                <h2><i class="fas fa-download"></i> Foto Mais Baixada</h2>
                <?php if ($fotoMaisBaixada): ?>
                    <img src="../uploads/aprovados/<?= htmlspecialchars($fotoMaisBaixada['foto_url']) ?>" alt="<?= htmlspecialchars($fotoMaisBaixada['descricao']) ?>" class="foto-preview">
                    <p><?= htmlspecialchars($fotoMaisBaixada['descricao']) ?></p>
                    <div class="stats">
                        <div class="stat">
                            <div class="number"><?= $fotoMaisBaixada['downloads'] ?></div>
                            <div class="label">Downloads</div>
                        </div>
                        <div class="stat">
                            <div class="number"><?= $fotoMaisBaixada['likes'] ?? '0' ?></div>
                            <div class="label">Likes</div>
                        </div>
                        <div class="stat">
                            <div class="number"><?= round($fotoMaisBaixada['downloads'] / ($fotoMaisBaixada['visualizacoes'] ?? 1) * 100, 1) ?>%</div>
                            <div class="label">Taxa de conversão</div>
                        </div>
                    </div>
                    <p class="autor">Por: <?= htmlspecialchars($fotoMaisBaixada['autor_nome']) ?></p>
                <?php else: ?>
                    <p>Nenhuma foto encontrada</p>
                <?php endif; ?>
            </div>-->
        </div>

        <!-- Top Fotógrafos -->
        <div class="hall-card" style="margin-top: 30px;">
            <h2><i class="fas fa-trophy"></i> Top Fotógrafos</h2>
            <div class="top-fotografos">
                <?php if (!empty($topFotografos)): ?>
                    <?php foreach ($topFotografos as $index => $fotografo): ?>
                        <div class="top-fotografo">
                            <div class="posicao"><?= $index + 1 ?></div>
                            <div class="foto" style="background-image: url('../uploads/perfil/<?= htmlspecialchars($fotografo['foto_de_perfil_url'] ?? 'default.jpg') ?>');"></div>
                            <div class="nome"><?= htmlspecialchars($fotografo['nome_completo']) ?></div>
                            <div class="uploads"><?= $fotografo['total_uploads'] ?> posts</div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum fotógrafo encontrado</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Menu dropdown
        document.getElementById('opcoes')?.addEventListener('change', function() {
            const selectedValue = this.value;
            if (selectedValue !== "") {
                window.location.href = selectedValue;
            }
        });

        // Animações simples
        const cards = document.querySelectorAll('.hall-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate__animated', 'animate__fadeInUp');
        });
    </script>
</body>

</html>