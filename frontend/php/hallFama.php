<?php
$fotografos = [
    ["nome" => "Pain Designer", "uploads" => 15],
    ["nome" => "Gacker", "uploads" => 100],
    ["nome" => "Edgar D. IT", "uploads" => 8],
    ["nome" => "Ana Sousa", "uploads" => 5]
];

$fotos = [
    ["file" => "natureza.jpg", "titulo" => "PirocaTecnica", "likes" => 120, "downloads" => 90],
    ["file" => "random.jpg", "titulo" => "Spider Man", "likes" => 85, "downloads" => 70],
    ["file" => "montanhas.jpg", "titulo" => "Costa do Sol", "likes" => 50, "downloads" => 110],
    ["file" => "sol.jpg", "titulo" => "Noite Cultural", "likes" => 74, "downloads" => 65],
    ["file" => "historia5.jpg", "titulo" => "Luz na Escuridão", "likes" => 67, "downloads" => 45],
    ["file" => "historia6.jpg", "titulo" => "Reflexos de Paz", "likes" => 99, "downloads" => 85]
];

// Determina o fotógrafo com mais uploads
$melhorFotografo = array_reduce($fotografos, function ($carry, $item) {
    return $item['uploads'] > $carry['uploads'] ? $item : $carry;
}, ["file", "nome" => "", "uploads" => 0]);

// Determina a foto com mais likes
$fotoMaisCurtida = array_reduce($fotos, function ($carry, $item) {
    return $item['likes'] > $carry['likes'] ? $item : $carry;
}, ["file" => "", "titulo" => "", "likes" => 0]);

// Determina a foto mais baixada
$fotoMaisBaixada = array_reduce($fotos, function ($carry, $item) {
    return $item['downloads'] > $carry['downloads'] ? $item : $carry;
}, ["file" => "", "titulo" => "", "downloads" => 0]);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall da Fama</title>
    <link rel="stylesheet" href="../css/hallFama.css">
    <link rel="stylesheet" href="../css/categorias.css">
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
        <h1>Hall da Fama</h1>

        <div class="card destaque">
            <h2>👑 Fotógrafo com mais uploads</h2>
            <p><strong><?php echo $melhorFotografo['nome']; ?></strong></p>
            <p>Uploads: <?php echo $melhorFotografo['uploads']; ?></p>
        </div>

        <div class="card destaque">
            <h2>💖 Foto com mais likes</h2>
            <img src="../images/<?php echo $fotoMaisCurtida['file']; ?>" alt="<?php echo $fotoMaisCurtida['titulo']; ?>">
            <p><strong><?php echo $fotoMaisCurtida['titulo']; ?></strong></p>
            <p>Likes: <?php echo $fotoMaisCurtida['likes']; ?></p>
        </div>

        <div class="card destaque">
            <h2>📥 Foto mais baixada</h2>
            <img src="../images/<?php echo $fotoMaisBaixada['file']; ?>" alt="<?php echo $fotoMaisBaixada['titulo']; ?>">
            <p><strong><?php echo $fotoMaisBaixada['titulo']; ?></strong></p>
            <p>Downloads: <?php echo $fotoMaisBaixada['downloads']; ?></p>
        </div>
    </div>

</body>

</html>