<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/perfil.css">
    <title>Perfil do Usuário</title>
</head>

<body>
    <div class="profile-container">
        <div class="profile-header">
            <img src="../images/perfil.png" alt="Foto de Perfil" class="profile-picture">
            <h1 class="username">Username</h1>
            <button class="edit-button" onclick="openEditProfile()">Editar Perfil</button>
        </div>
        <div class="profile-details">
            <p><strong>Email:</strong> user@example.com</p>
            <p><strong>Frase Favorita:</strong> "O segredo do sucesso é a constância no propósito."</p>
            <p><strong>Localização:</strong> São Paulo, Brasil</p>
            <p><strong>Data de Cadastro:</strong> 01/01/2022</p>
        </div>
    </div>
    <script src="../js/profile.js"></script>
</body>

</html>