// Mock de fotógrafos registrados
const photographers = [
    { id: 1, name: "Pain Deigner", uploads: 34, avatar: "images/perfil.png" },
    { id: 2, name: "Ameon.dg", uploads: 12, avatar: "images/perfil.png" },
    { id: 3, name: "Edgar D. IT", uploads: 45, avatar: "images/perfil.png" },
    { id: 4, name: "Gacker", uploads: 25, avatar: "images/perfil.png" },
    { id: 5, name: "Penisvaldo", uploads: 18, avatar: "images/perfil.png" },
];

// Função para carregar a lista
function loadPhotographers(list) {
    const container = document.getElementById("photographers-list");
    container.innerHTML = ""; // Limpa o container

    list.forEach((photographer) => {
        const listItem = document.createElement("li");
        listItem.className = "photographer-item";
        listItem.innerHTML = `
            <div class="photographer-info">
                <img src="${photographer.avatar}" alt="${photographer.name}" class="photographer-avatar">
                <div class="photographer-name">${photographer.name}</div>
            </div>
            <div>
                <div>Uploads: ${photographer.uploads}</div>
            </div>
            <div class="action-buttons">
                <button class="action-button ban-button" onclick="banPhotographer(${photographer.id})">Banir</button>
                <button class="action-button remove-uploader-button" onclick="removeUploader(${photographer.id})">Remover Uploader</button>
            </div>
        `;
        container.appendChild(listItem);
    });
}

// Função para banir fotógrafo
function banPhotographer(id) {
    alert(`Usuário com ID ${id} foi banido.`);
    // Implementação de backend aqui
}

// Função para remover título de uploader
function removeUploader(id) {
    alert(`Título de uploader removido para o usuário com ID ${id}.`);
    // Implementação de backend aqui
}

// Função para ordenar fotógrafos
function sortPhotographers() {
    const sortBy = document.getElementById("sort-options").value;
    let sortedPhotographers = [...photographers];
    if (sortBy === "name") {
        sortedPhotographers.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy === "uploads") {
        sortedPhotographers.sort((a, b) => b.uploads - a.uploads);
    }
    loadPhotographers(sortedPhotographers);
}

// Carrega a lista na inicialização
window.onload = () => {
    loadPhotographers(photographers);
};
