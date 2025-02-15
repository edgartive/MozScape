// Simulação de uploads
const uploads = [
    {
        thumbnail: "https://via.placeholder.com/300x200",
        name: "Pôr do Sol na Praia",
        author: "João Silva",
        province: "Santa Catarina",
        likes: 120
    },
    {
        thumbnail: "https://via.placeholder.com/300x200",
        name: "Montanhas Nevadas",
        author: "Maria Oliveira",
        province: "Rio Grande do Sul",
        likes: 85
    },
    {
        thumbnail: "https://via.placeholder.com/300x200",
        name: "Café da Manhã na Serra",
        author: "Carlos Almeida",
        province: "Paraná",
        likes: 50
    },
    {
        thumbnail: "https://via.placeholder.com/300x200",
        name: "Cachoeira Encantada",
        author: "Ana Costa",
        province: "Minas Gerais",
        likes: 200
    }
];

// Função para carregar os uploads na tela
function loadUploads() {
    const uploadsList = document.getElementById("uploads-list");

    uploads.forEach(upload => {
        const uploadItem = document.createElement("div");
        uploadItem.classList.add("upload-item");

        uploadItem.innerHTML = `
            <img class="upload-thumbnail" src="${upload.thumbnail}" alt="${upload.name}">
            <div class="upload-info">
                <div class="upload-name">${upload.name}</div>
                <div class="upload-author">Autor: ${upload.author}</div>
                <div class="upload-province">Província: ${upload.province}</div>
                <button class="like-button" onclick="likeUpload(${upload.likes})">
                    <i class="fas fa-heart"></i> ${upload.likes}
                </button>
            </div>
        `;

        uploadsList.appendChild(uploadItem);
    });
}

// Função para curtir o upload
function likeUpload(likes) {
    likes++;
    alert(`O número de likes foi atualizado para: ${likes}`);
}

// Função para filtrar uploads
function filterUploads(event) {
    const searchInput = document.getElementById("search-input").value.toLowerCase();
    const filterSelect = document.getElementById("filter-select").value;

    const filteredUploads = uploads.filter(upload => {
        const matchesSearch = upload.name.toLowerCase().includes(searchInput) ||
            upload.author.toLowerCase().includes(searchInput) ||
            upload.province.toLowerCase().includes(searchInput);

        let matchesFilter = true;
        if (filterSelect === 'photographer') {
            matchesFilter = upload.author.toLowerCase().includes(searchInput);
        } else if (filterSelect === 'province') {
            matchesFilter = upload.province.toLowerCase().includes(searchInput);
        } else if (filterSelect === 'name') {
            matchesFilter = upload.name.toLowerCase().includes(searchInput);
        }

        return matchesSearch && matchesFilter;
    });

    displayFilteredUploads(filteredUploads);
}

// Função para exibir os uploads filtrados
function displayFilteredUploads(filteredUploads) {
    const uploadsList = document.getElementById("uploads-list");
    uploadsList.innerHTML = '';

    filteredUploads.forEach(upload => {
        const uploadItem = document.createElement("div");
        uploadItem.classList.add("upload-item");

        uploadItem.innerHTML = `
            <img class="upload-thumbnail" src="${upload.thumbnail}" alt="${upload.name}">
            <div class="upload-info">
                <div class="upload-name">${upload.name}</div>
                <div class="upload-author">Autor: ${upload.author}</div>
                <div class="upload-province">Província: ${upload.province}</div>
                <button class="like-button" onclick="likeUpload(${upload.likes})">
                    <i class="fas fa-heart"></i> ${upload.likes}
                </button>
            </div>
        `;

        uploadsList.appendChild(uploadItem);
    });
}

// Função para abrir o painel de explicação
function toggleInfoPanel() {
    const panel = document.getElementById("info-panel");
    panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
}

// Inicializar a tela
window.onload = loadUploads;
