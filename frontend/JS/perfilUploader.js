document.addEventListener('DOMContentLoaded', () => {
    const favoritarBtn = document.getElementById('favoritar-uploader');

    // Favoritar o uploader
    favoritarBtn.addEventListener('click', () => {
        if (favoritarBtn.classList.contains('favoritado')) {
            // Remove o estado de favorito
            favoritarBtn.classList.remove('favoritado');
            favoritarBtn.textContent = 'Adicionar aos Favoritos';
            favoritarBtn.style.backgroundColor = '#ffbf00';
        } else {
            // Adiciona o estado de favorito
            favoritarBtn.classList.add('favoritado');
            favoritarBtn.textContent = 'Favoritado';
            favoritarBtn.style.backgroundColor = '#007bff';
        }
    });
});
