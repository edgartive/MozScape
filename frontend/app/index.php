<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MozScape</title>
    <link rel="icon" href="images/sea.png" type="image/png">

    <link rel="stylesheet" href="styles.css">

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
                <img src="images/logo-black.png" alt="">
            </div>
        </a>
        <a href="login.php" style="text-decoration: none;">
            <div class="perfil-usuario">
                <div class="foto-perfil" style="background-image: url('images/perfil.png');"></div>
            </div>
        </a>
        <nav>
            <ul>
                <li><a href="index.html" id="destacar">Início</a></li>
                <li><a href="categorias.html">Categorias</a></li>
                <li><a href="sobre.html">Sobre</a></li>
                <li><a href="contacto.html">Contactos</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h2 class="descubra">Descubra a beleza do nosso país através das lentes!</h2>
            <h3>Descarregue as fotografias de paisagem deslumbrantes em alta resolução</h3>
            <a href="categorias.html" class="btn">Explorar Fotografias</a>
        </section>

        <section class="categorias-destaque">
            <h2 id="centerText">Categorias Populares</h3>
                <div class="grid">
                    <div class="categoria">
                        <a href="subcategorias/natureza.html"> <img src="/images/natureza.jpg" alt="Natureza">
                        </a>
                        <p id="whiteText">Natureza</p>
                    </div>
                    <div class="categoria">
                        <a href="subcategorias/cidades.html"> <img src="images/cidades.jpg" alt="Cidades"></a>
                        <p id="whiteText">Cidades</p>
                    </div>
                    <div class="categoria">
                        <a href="subcategorias/arquitetura.html"><img src="images/montanhas.jpg" alt="Montanhas">
                        </a>
                        <p id="whiteText">Céu e nuvens</p>
                    </div>
                </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Pain Designer</p>
    </footer>
</body>

</html>