<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="request-uploader.css">
    <title>Solicitar Tornar-se Uploader</title>
</head>
<body>
    <div class="form-container">
        <h1>Solicitar Tornar-se um Uploader</h1>
        <form id="uploaderRequestForm">
            <!-- Motivação -->
            <div class="form-group">
                <label for="motivation">Sua Motivação para se Tornar um Fotógrafo</label>
                <textarea id="motivation" name="motivation" rows="4" placeholder="Descreva sua motivação..." required></textarea>
            </div>

            <!-- Frase Favorita -->
            <div class="form-group">
                <label for="favoritePhrase">Sua Frase Favorita</label>
                <input type="text" id="favoritePhrase" name="favoritePhrase" placeholder="Digite sua frase favorita..." required>
            </div>

            <!-- Upload de 3 fotos -->
            <div class="form-group">
                <label for="photoUploads">Faça o Upload de 3 das Suas Melhores Fotos</label>
                <input type="file" id="photoUploads" name="photoUploads" accept="image/*" multiple required>
                <small>Por favor, faça o upload de exatamente 3 fotos (JPG, PNG).</small>
            </div>

            <!-- Termos e Condições -->
            <div class="form-group checkbox-group">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">Eu concordo com os termos e condições para me tornar um uploader.</label>
                
            </div>                <small>Ao concordar, você reconhece que seus envios serão avaliados e poderão ser exibidos publicamente.</small>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="authorship" name="authorship" required>
                <label for="authorship">Eu confirmo que as fotos enviadas são de minha autoria.</label>
                
            </div>

            <!-- Botão de Enviar -->
            <button type="submit" class="submit-button">Enviar Solicitação</button>
        </form>
    </div>
</body>
</html>
