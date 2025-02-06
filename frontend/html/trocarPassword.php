<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/trocarPassword.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Trocar Palavra Passe</title>
</head>

<body>
    <div class="change-password-container">
        <h1>Trocar Palavra Passe</h1>
        <form id="change-password-form">
            <div class="form-group">
                <label for="old-password">Palavra Passe Atual:</label>
                <input type="password" id="old-password" placeholder="Digite a senha atual" required>
            </div>
            <div class="form-group">
                <label for="new-password">Nova Palavra Passe:</label>
                <input type="password" id="new-password" placeholder="Digite a nova senha" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirmar Nova Palavra Passe:</label>
                <input type="password" id="confirm-password" placeholder="Repita a nova senha" required>
            </div>
            <button type="submit" class="save-button">Alterar Palavra Passe</button>
        </form>
    </div>
    <script src="../js/trocarPassword.js"></script>
</body>

</html>