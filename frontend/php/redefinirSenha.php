<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="redefinirSenha.css">
</head>
<body>
    <div class="reset-password-container">
        <h2>Redefinir Senha</h2>
        <form id="resetPasswordForm">
            <div class="form-group">
                <label for="verificationCode">Código de Verificação</label>
                <input type="text" id="verificationCode" name="verificationCode" placeholder="Digite o código recebido" required>
            </div>

            <div class="form-group">
                <label for="newPassword">Nova Senha</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="Digite sua nova senha" required>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirmar Nova Senha</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirme sua nova senha" required>
            </div>

            <button type="submit" class="reset-button">Confirmar Redefinição</button>
        </form>
    </div>
</body>
</html>
