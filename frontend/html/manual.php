<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual de Instruções</title>
    <link rel="stylesheet" href="../css/manual.css">
</head>

<body>
    <header>
        <h1>Manual de Instruções</h1>
    </header>

    <main class="manual-container">
        <div class="manual-slider">
            <!-- Painel 1 -->
            <div class="manual-panel" id="panel1">
                <h2>Página Inicial</h2>
                <p>A página inicial exibe as categorias principais e fotos em destaque. Clique em uma categoria para explorar as imagens relacionadas.</p>
            </div>

            <!-- Painel 2 -->
            <div class="manual-panel" id="panel2">
                <h2>Perfil do Uploader</h2>
                <p>Veja as fotos enviadas por um uploader, suas informações e adicione-o aos seus favoritos clicando no botão correspondente.</p>
            </div>

            <!-- Painel 3 -->
            <div class="manual-panel" id="panel3">
                <h2>Detalhes da Foto</h2>
                <p>Veja informações detalhadas da foto, curta, adicione aos favoritos ou a coleções e explore imagens semelhantes.</p>
            </div>

            <!-- Painel 4 -->
            <div class="manual-panel" id="panel4">
                <h2>Configurações</h2>
                <p>Edite suas informações pessoais, mude sua senha e configure o modo de exibição (dark ou light).</p>
            </div>
        </div>

        <!-- Botões de Navegação -->
        <button class="nav-btn left-btn" onclick="prevPanel()">&#9664;</button>
        <button class="nav-btn right-btn" onclick="nextPanel()">&#9654;</button>
    </main>

    <footer>
        <p>&copy; 2024 Paisagens do Meu País. Todos os direitos reservados.</p>
    </footer>

    <script src="../js/manual.js"></script>
</body>

</html>