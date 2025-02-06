<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações</title>
    <link rel="stylesheet" href="../css/settings.css">
</head>

<body>
    <div class="settings-container">
        <!-- Menu Lateral -->
        <nav class="menu">
            <button class="menu-item" data-target="edit-profile">Editar Perfil</button>
            <button class="menu-item" data-target="security">Segurança</button>
            <button class="menu-item" data-target="personal-data">Dados Pessoais</button>
        </nav>

        <!-- Painéis -->
        <div class="panels">
            <!-- Painel de Editar Perfil -->
            <div class="settings-panel" id="edit-profile">
                <h2>Editar Perfil</h2>
                <form>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Seu username" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Seu email" required>
                    <label for="profile-picture">Foto de Perfil:</label>
                    <input type="file" id="profile-picture" name="profile-picture" accept="image/*">
                    <label for="bio">Frase Favorita:</label>
                    <textarea id="bio" name="bio" rows="3" placeholder="Escreva sua frase favorita"></textarea>
                    <button type="submit">Salvar Alterações</button>
                </form>
            </div>

            <!-- Painel de Segurança -->
            <div class="settings-panel" id="security">
                <h2>Segurança</h2>
                <form>
                    <label for="current-password">Senha Atual:</label>
                    <input type="password" id="current-password" name="current-password" placeholder="Senha atual" required>
                    <label for="new-password">Nova Senha:</label>
                    <input type="password" id="new-password" name="new-password" placeholder="Nova senha" required>
                    <label for="confirm-password">Confirme a Nova Senha:</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirme nova senha" required>
                    <button type="submit">Atualizar Senha</button>
                </form>
            </div>

            <!-- Painel de Dados Pessoais -->
            <div class="settings-panel" id="personal-data">
                <h2>Dados Pessoais</h2>
                <form>
                    <label for="name">Nome Completo:</label>
                    <input type="text" id="name" name="name" placeholder="Seu nome completo" required>
                    <label for="gender">Sexo:</label>
                    <select id="gender" name="gender">
                        <option value="male">Masculino</option>
                        <option value="female">Feminino</option>
                        <option value="other">Outro</option>
                    </select>
                    <label for="birth-date">Data de Nascimento:</label>
                    <input type="date" id="birth-date" name="birth-date" required>
                    <button type="submit">Salvar Dados</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/settings.js"></script>
</body>

</html>