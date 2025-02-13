<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Uploader</title>
    <link rel="stylesheet" href="../css/perfilPublicoDeUploader.css">
</head>

<body>
    <header>
        <h1>Perfil do Uploader</h1>
    </header>

    <main class="perfil-container">
        <!-- Dados do Uploader -->
        <section class="uploader-info">
            <img src="../images/profile.jpg" alt="Foto de Perfil" class="foto-perfil">
            <h2>Nome do Uploader</h2>
            <p><strong>Email:</strong> uploader@email.com</p>
            <p><strong>Frase Favorita:</strong> "Fotografar é eternizar momentos."</p>
            <p><strong>Total de Publicações:</strong> 25</p>
            <button id="favoritar-uploader" class="favoritar-btn">Adicionar aos Favoritos</button>
        </section>

        <!-- Publicações do Uploader -->
        <section class="publicacoes">
            <h2>Publicações de Nome do Uploader</h2>
            <div class="grid-publicacoes">
                <!-- Exemplo de publicação -->
                <div class="publicacao">
                    <img src="../images/natureza.jpg" alt="Publicação">
                    <p>Título da Foto</p>
                </div>
                <div class="publicacao">
                    <img src="../images/arquitetura.jpg" alt="Publicação">
                    <p>Título da Foto</p>
                </div>
                <div class="publicacao">
                    <img src="../images/reflexo.jpg" alt="Publicação">
                    <p>Título da Foto</p>
                </div>
                <!-- Adicione mais publicações conforme necessário -->
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Paisagens do Meu País. Todos os direitos reservados.</p>
    </footer>

    <script src="../js/perfilUploader.js"></script>
</body>

</html>