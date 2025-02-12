<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="registroUser.css">
</head>

<body>
    <h1>Cadastrar Usuário</h1>
    <form method="POST" action="processar_cadastro.php" enctype="multipart/form-data">
        <label for="nome_completo">Nome Completo:</label>
        <input type="text" id="nome_completo" name="nome_completo" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="foto_de_perfil">Foto de Perfil:</label>
        <input type="file" id="foto_de_perfil" name="foto_de_perfil" accept="image/*" required><br>

        <label for="frase_favorita">Frase Favorita:</label>
        <input type="text" id="frase_favorita" name="frase_favorita"><br>

        <label for="uploader">Uploader:</label>
        <input type="checkbox" id="uploader" name="uploader" value="1"><br>

        <label for="biografia">Biografia:</label>
        <textarea id="biografia" name="biografia"></textarea><br>

        <label for="links">Links:</label>
        <textarea id="links" name="links"></textarea><br>

        <button type="submit">Cadastrar</button>
    </form>
</body>

</html>