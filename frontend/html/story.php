<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias</title>
    <link rel="icon" href="../images/sea.png" type="image/png">
    <link rel="stylesheet" href="../css/story.css">
    <link rel="stylesheet" href="../fontes/webfonts/css/all.css">
    <style>
        /* ... (CSS styles from previous response, included below) ... */
    </style>
</head>

<body>

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
                    <li><a href="categorias.php">Explorar</a></li>
                    <li><a href="story.php" id="destacar">Story</a></li>
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
                <a href="uploadStory.php" class="upload-btn" style="color: white;">
                    <i class="fas fa-upload"></i> <!-- Ícone de upload -->
                </a>


            </form>
        </section>



        <section class="categorias-destaque">
            <div class="grid">
                <div class="categoria" data-categoria="natureza">
                    <a href="subcategorias/natureza.html">
                        <img src="../images/natureza.jpg" alt="Natureza">
                    </a>
                    <div class="info-overlay">
                        <P>Título: Pika dura</P>
                        <p class="autor-provincia">Autor: Gacker </p>
                        <p> Província: Gaza</p>
                        <button class="like-button">
                            <i class="fas fa-heart"></i>
                            <span class="like-count">85</span>
                        </button>
                    </div>
                </div>

                <div class="categoria" data-categoria="cidades">
                    <img src="../images/cidades.jpg" alt="Cidades">
                    <div class="info-overlay">
                        <p class="titulo">Cidades</p>
                        <p class="autor-provincia">Autor: Autor Exemplo, Província: Maputo</p>
                        <button class="like-button">
                            <i class="fas fa-heart"></i>
                            <span class="like-count">5</span>
                        </button>
                    </div>
                </div>

                <div class="categoria" data-categoria="arquitetura">
                    <img src="../images/arquitetura.jpg" alt="Arquitetura">
                    <div class="info-overlay">
                        <p class="titulo">Arquitetura</p>
                        <p class="autor-provincia">Autor: Autor Exemplo, Província: Maputo</p>
                        <button class="like-button">
                            <i class="fas fa-heart"></i>
                            <span class="like-count">15</span>
                        </button>
                    </div>
                </div>
                <div class="categoria" data-categoria="praias">
                    <img src="../images/Praias.jpg" alt="Litoral e Praias">
                    <div class="info-overlay">
                        <p class="titulo">Praias</p>
                        <p class="autor-provincia">Autor: Autor Exemplo, Província: Maputo</p>
                        <button class="like-button">
                            <i class="fas fa-heart"></i>
                            <span class="like-count">20</span>
                        </button>
                    </div>
                </div>
                <div class="categoria" data-categoria="sol">
                    <img src="../images/Carros.jpg" alt="Sol">
                    <div class="info-overlay">
                        <p class="titulo">Carros</p>
                        <p class="autor-provincia">Autor: Autor Exemplo, Província: Maputo</p>
                        <button class="like-button">
                            <i class="fas fa-heart"></i>
                            <span class="like-count">8</span>
                        </button>
                    </div>
                </div>
                <div class="categoria" data-categoria="ceu">
                    <img src="../images/ceu.jpg" alt="Céu e Nuvens">
                    <div class="info-overlay">
                        <p class="titulo">Céu e Nuvens</p>
                        <p class="autor-provincia">Autor: Autor Exemplo, Província: Maputo</p>
                        <button class="like-button">
                            <i class="fas fa-heart"></i>
                            <span class="like-count">12</span>
                        </button>
                    </div>
                </div>
                <div class="categoria" data-categoria="reflexo">
                    <img src="../images/reflexo.jpg" alt="Reflexões e Espelhos d'Água">
                    <div class="info-overlay">
                        <p class="titulo">Reflexões e Espelhos d'Água</p>
                        <p class="autor-provincia">Autor: Autor Exemplo, Província: Maputo</p>
                        <button class="like-button">
                            <i class="fas fa-heart"></i>
                            <span class="like-count">7</span>
                        </button>
                    </div>
                </div>
                <div class="categoria" data-categoria="random">
                    <img src="../images/random.jpg" alt="Random">
                    <div class="info-overlay">
                        <p class="titulo">Random</p>
                        <p class="autor-provincia">Autor: Autor Exemplo, Província: Maputo</p>
                        <button class="like-button">
                            <i class="fas fa-heart"></i>
                            <span class="like-count">25</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <footer>
            <p>&copy; 2024 Pain Designer</p>
        </footer>

        <script>
            // JavaScript (if needed for dynamic like counts)
            function pesquisarCategoria() {
                var input = document.getElementById('pesquisa').value.toLowerCase();
                var categorias = document.querySelectorAll('.categoria');

                categorias.forEach(function(categoria) {
                    var categoriaNome = categoria.getAttribute('data-categoria').toLowerCase();
                    if (categoriaNome.includes(input)) {
                        categoria.style.display = 'block';
                    } else {
                        categoria.style.display = 'none';
                    }
                });
            }
        </script>
    </body>

</html>