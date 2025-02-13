<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload da História</title>
    <link rel="stylesheet" href="../css/uploadStory.css">
</head>

<body>
    <header>
        <h1 id="destacar">Upload da História</h1>
    </header>

    <main>

        <div class="form-container">
            <h2>Carregue sua Foto e Adicione a História</h2>
            <form id="uploadForm">
                <div class="form-group">
                    <label for="photoUpload">Selecione a Foto:</label>
                    <input type="file" id="photoUpload" name="photoUpload" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="title">Título:</label>
                    <input class="inputF" type="text" id="title" name="title" placeholder="Digite o título" required>
                </div>

                <div class="form-group">
                    <label for="description">Descrição:</label>
                    <textarea class="inputF" id="description" name="description" placeholder="Conte sobre o local e a história"
                        required></textarea>
                </div>

                <div class="form-group">
                    <label for="hashtags">Hashtags:</label>
                    <input class="inputF" type="text" id="hashtags" name="hashtags" placeholder="Exemplo: #viagem, #aventura" required>
                </div>

                <div class="form-group">
                    <label for="date">Data da Foto:</label>
                    <input class="inputF" type="date" id="date" name="date" required>
                </div>

                <div class="form-group">
                    <label for="location">Localização (opcional):</label>
                    <input class="inputF" type="text" id="location" name="location" placeholder="Digite a localização">
                </div>

                <button type="submit" class="btn-upload">Enviar</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Sua Plataforma</p>
    </footer>

</body>

</html>