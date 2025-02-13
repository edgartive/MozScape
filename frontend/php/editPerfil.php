<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/perfil.css">
    <title>Editar Perfil</title>
</head>

<body>
    <div class="edit-profile-container">
        <h1>Editar Perfil</h1>
        <form id="edit-profile-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" value="Username">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" value="user@example.com">
            </div>
            <div class="form-group">
                <label for="favorite-quote">Frase Favorita:</label>
                <textarea id="favorite-quote" rows="3">"O segredo do sucesso é a constância no propósito."</textarea>
            </div>
            <div class="form-group">
                <label for="location">Localização:</label>
                <input type="text" id="location" value="São Paulo, Brasil">
            </div>
            <div class="form-group">
                <label for="profile-pic">Foto de Perfil:</label>
                <input type="file" id="profile-pic">
            </div>
            <button type="submit" class="save-button">Salvar Alterações</button>
        </form>
    </div>
    <script src="../js/profile.js"></script>
</body>

</html>