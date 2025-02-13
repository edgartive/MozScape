document.getElementById('change-password-form').addEventListener('submit', function (event) {
    event.preventDefault();

    const oldPassword = document.getElementById('old-password').value;
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    // Simulação de validação (trocar por backend para verificar senha atual)
    const currentPassword = "senhaAtual"; // Exemplo estático

    if (oldPassword !== currentPassword) {
        alert("A palavra passe atual está incorreta.");
        return;
    }

    if (newPassword.length < 6) {
        alert("A nova palavra passe deve ter pelo menos 6 caracteres.");
        return;
    }

    if (newPassword !== confirmPassword) {
        alert("As novas palavras passe não coincidem.");
        return;
    }

    // Simular salvamento (substituir por backend para atualizar a senha)
    console.log("Senha alterada com sucesso!");
    alert("A palavra passe foi alterada com sucesso.");
    // Redirecionar ou limpar formulário
    document.getElementById('change-password-form').reset();
});
