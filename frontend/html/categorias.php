<?php
$placeholder = 'Water 7';

?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias</title>
    <link rel="icon" href="../images/sea.png" type="image/png">
    <link rel="stylesheet" href="../css/categorias.css">
    <link rel="stylesheet" href="../fontes/webfonts/css/all.css">

</head>

<body>
    <header>
        <h2 style="text-align: center;"> </h2>
        <a href="index.html">
            <div id="back-home" style="position: absolute; top: 10px; left: 10px;
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 5px 10px;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);">
                <img src="images/logo-black.png" alt="">
            </div>
        </a>
        <div class="perfil-usuario">
            <div class="foto-perfil" style="background-image: url('images/perfil.png');"></div>

        </div>
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
        <form onsubmit="return false;"> <!-- Impede o envio do formulário -->
            <select id="categoria" name="categoria">
                <option value="categoria1">Tudo</option>
                <option value="categoria2">Categorias</option>
                <option value="categoria3">Fotógrafo</option>
            </select>
            <!-- Campo de pesquisa -->
            <input type="text" id="pesquisa" name="pesquisa" placeholder="Pesquisar por categorias..."
                onkeyup="pesquisarCategoria()">
            <!-- Ícone de pesquisa -->
            <button type="submit">
                <i class="fas fa-search"></i> <!-- Ícone de pesquisa -->
            </button>
            <a href="upload.php" class="upload-btn" style="color: white;">
                <i class="fas fa-upload"></i> <!-- Ícone de upload -->
            </a>

            <!-- Botão de Informação com Ícone -->
            <button type="button" id="infoButton" onclick="abrirJOptionPane()">
                <i class="fas fa-info-circle"></i>
            </button>

        </form>
        <!-- Modal de Informação -->
        <div id="infoModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="fecharInfo()">&times;</span>
                <p>Esta tela permite explorar diferentes categorias de fotografias enviadas pelos usuários. Você pode buscar categorias específicas usando a barra de pesquisa ou adicionar suas próprias imagens através do botão de upload.</p>
            </div>
        </div>
    </section>

    <!-- Painel de Informação -->

    </section>

    <!-- Grid de Categorias -->
    <section class="categorias-destaque">
        <h2 id="centerText">Escolha uma categoria para explorar</h2>
        <div id="hastags"> <a href="">#praia</a> <a href="">#sunset</a> <a href="">#sunrise</a> <a href="">#agua</a> <a
                href="">#noite</a> <a href="">#xiaomi </a> <a href=""> #flores</a> <a href="">#predio</a> <a
                href="">#samsung</a><a href="">#maputo</a> <a href="">#animal</a></div>
        <p> </p>
        <div class="grid">
            <div class="categoria" data-categoria="natureza">
                <a href="subcategorias/natureza.html"><img src="../images/natureza.jpg" alt="Natureza"></a>
                <p>Natureza</p>
            </div>
            <div class="categoria" data-categoria="cidades">
                <img src="../images/cidades.jpg" alt="Cidades">
                <p>Cidades</p>
            </div>
            <div class="categoria" data-categoria="arquitetura">
                <img src="../images/arquitetura.jpg" alt="Arquitetura">
                <p>Arquitetura</p>
            </div>
            <div class="categoria" data-categoria="praias">
                <img src="../images/Praias.jpg" alt="Litoral e Praias">
                <p>Praias</p>
            </div>
            <div class="categoria" data-categoria="sol">
                <img src="../images/Carros.jpg" alt="Sol">
                <p>Carros</p>
            </div>
            <div class="categoria" data-categoria="ceu">
                <img src="../images/ceu.jpg" alt="Céu e Nuvens">
                <p>Céu e Nuvens</p>
            </div>
            <div class="categoria" data-categoria="reflexo">
                <img src="../images/reflexo.jpg" alt="Reflexões e Espelhos d'Água">
                <p>Reflexões e Espelhos d'Água</p>
            </div>
            <div class="categoria" data-categoria="random">
                <img src="../images/random.jpg" alt="Random">
                <p>Random</p>
            </div>
        </div>
    </section>

    <!-- Rodapé -->
    <footer>
        <p>&copy; 2024 Pain Designer</p>
    </footer>
    <script src="../js/script.js"></script>

</body>

</html>