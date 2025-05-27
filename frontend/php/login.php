<!DOCTYPE html>
<html lang="pt">
<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    // Se o usuário já estiver logado, redireciona para a última página visitada ou para a página inicial
    $redirect = isset($_SESSION['last_page']) ? $_SESSION['last_page'] : '../index.php';
    echo "<script>window.location.href = '$redirect';</script>";
    exit;
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <header>
        <h1>Bem-vindo!</h1>
    </header>

    <main>
        <div class="login-container">
            <h2>Login</h2>
            <form id="login-form" method="POST" action="../php/processos/processar_login.php">
                <div class="form-group">
                    <label for="username">Usuário</label>
                    <input type="text" id="username" name="username" placeholder="Digite seu usuário" required>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
                </div>

                <div class="form-group options">
                    <a href="recuperarSenha.html" class="forgot-password">Esqueceu a senha?</a>
                </div>

                <div class="form-group">
                    <button type="submit">Entrar</button>
                </div>
            </form>

            <div class="signup-option">
                <p>Não tem uma conta? <a href="registroUser.php">Cadastre-se</a></p>
            </div>
        </div>
    </main>

    <footer>
        <p>© 2024 Mozscape - Todos os direitos reservados</p>
    </footer>
</body>

</html>