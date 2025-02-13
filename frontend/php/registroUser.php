<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="registroUser.css">
</head>
<body>
    <header>
        <h1>Cadastro de Usuário</h1>
    </header>

    <main>
        <div class="form-container">
            <h2>Crie sua conta</h2>
            <form id="user-registration-form">
                <div class="form-group">
                    <label for="nome">Nome do usuário</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite seu nome de usuario" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Crie uma senha" required>
                </div>

                <div class="form-group">
                    <label for="confirmar-senha">Confirmar Senha</label>
                    <input type="password" id="confirmar-senha" name="confirmar-senha" placeholder="Repita a senha" required>
                </div>

                <div class="form-group">
                    <label for="data-nascimento">Data de Nascimento</label>
                    <input type="date" id="data-nascimento" name="data-nascimento" required>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="ex: 8X-XXX-XXXX" required>
                </div>

                

                <div class="form-group">
                    <button  type="submit">Cadastrar</button>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p>© 2024 - Todos os direitos reservados</p>
    </footer>
</body>
</html>
