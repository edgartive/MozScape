<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Upload</title>
    <link rel="stylesheet" href="statusUpload.css">
</head>

<body>
    <header>
        <h1 id="destacar">Estado de Upload</h1>
    </header>

    <main>
        <div class="upload-status-container">
            <!-- Verifique se os caminhos para as imagens estão corretos -->
            <div class="upload-status-item">
                <img src="../images/arquitetura.jpg" alt="Upload 1" class="upload-thumbnail">
                <div class="upload-info">
                    <h3>Viagem à Montanha</h3>
                    <p class="upload-date">12/12/2024</p>
                    <p class="upload-status status-pendente">Pendente</p>
                </div>
            </div>
            <div class="upload-status-item">
                <img src="../images/Carros.jpg" alt="Upload 2" class="upload-thumbnail">
                <div class="upload-info">
                    <h3>Aventura na Praia</h3>
                    <p class="upload-date">10/12/2024</p>
                    <p class="upload-status status-negado">Negado</p>
                </div>
            </div>
            <div class="upload-status-item">
                <img src="../images/random.jpg" alt="Upload 3" class="upload-thumbnail">
                <div class="upload-info">
                    <h3>Exploração da Cidade</h3>
                    <p class="upload-date">08/12/2024</p>
                    <p class="upload-status status-pendente">Pendente</p>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Pain Designer</p>
    </footer>

</body>

</html>
