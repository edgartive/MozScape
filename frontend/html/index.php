<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MozScape</title>
    <link rel="icon" href="../images/sea.png" type="image/png">

    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../fontes/webfonts/css/all.css">

</head>

<body>
    <header>
        <h2 style="text-align: center;"> </h2>
        <a href="index.html">
            <div class="back-home" style="position: absolute; top: 10px; left: 10px;
          
            align-items: center;
            background-color: rgba(255, 255, 255, 0);
            padding: 5px 10px;
            border-radius: 20px;
            ">
                <img src="../images/logo-black.png" alt="">
            </div>
        </a>
        <div class="perfil-usuario">
            <div class="foto-perfil" style="background-image: url('../images/perfil.png');"></div>
            <select id="opcoes" name=" ">
                <option value=""></option> <!-- Opção padrão -->
                <option value="visaoPerfil.php">Meu perfil</option>
                <option value="favoritosECollections.php">Favoritos</option>
                <option value="definicoes.php">Definições</option>
                <option value="statusUpload.php">Ver pedidos</option>
                <option value="ajuda_faq.php">Ajuda</option>
                <option value="contacto.php">Contactos</option>
                <option value="manual.php">Manual de instruções</option>
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
                <li><a href="index.php" id="destacar">Início</a></li>
                <li><a href="categorias.php">Explorar</a></li>
                <li><a href="story.php">Story</a></li>
                <li><a href="hallFama.php">Hall da fama</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h2 class="descubra">Descubra a beleza do nosso país através das lentes!</h2>
            <h3>Descarregue as fotografias de paisagem deslumbrantes em alta resolução</h3>
            <a href="categorias.html" class="btn">Explorar Fotog afias</a>
        </section>

        <section class="categorias-destaque">
            <h2 id="centerText">Categorias Populares</h3>
                <div class="grid">
                    <div class="categoria">
                        <a href="subcategorias/natureza.php"> <img src="../images/natureza.jpg" alt="Natureza">
                        </a>
                        <p id="whiteText">Natureza</p>
                    </div>
                    <div class="categoria">
                        <a href="subcategorias/cidades.html"> <img src="../images/cidades.jpg" alt="Cidades"></a>
                        <p id="whiteText">Cidades</p>
                    </div>
                    <div class="categoria">
                        <a href="subcategorias/arquitetura.html"><img src="../images/montanhas.jpg" alt="Montanhas">
                        </a>
                        <p id="whiteText">Céu e nuvens</p>
                    </div>
                </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 MozScape</p>
    </footer>
</body>

</html>