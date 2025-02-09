function openEditProfile() {
    // Redireciona para a página de edição de perfil
    window.location.href = "editPerfil.php";
}

document.getElementById('edit-profile-form')?.addEventListener('submit', function (event) {
    event.preventDefault();

    // Obter valores dos campos
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const favoriteQuote = document.getElementById('favorite-quote').value;
    const location = document.getElementById('location').value;

    // Simular salvar os dados (seria necessário um backend)
    console.log('Perfil atualizado:', { username, email, favoriteQuote, location });

    // Redirecionar de volta ao perfil após salvar
    alert("Perfil atualizado com sucesso!");
    window.location.href = "visaoPerfil.php";
});
