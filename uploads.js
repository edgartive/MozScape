// Dados de exemplo de uploads
const uploads = [
    { id: 1, fileName: 'Natureza.jpg', uploader: 'Pain Designer', uploadDate: '2024-01-15', thumbnail: 'images/natureza.jpg', description: 'Uma foto incrível da natureza.' },
    { id: 2, fileName: 'arquitetura.jpg', uploader: 'Gacker', uploadDate: '2024-02-18', thumbnail: 'images/arquitetura.jpg', description: 'Foto urbana com arquitetura moderna.' },
    { id: 3, fileName: 'Ceu.jpg', uploader: 'Edgar D. IT', uploadDate: '2024-03-20', thumbnail: 'images/ceu.jpg', description: 'Paisagem surrealista ao amanhecer.' },
    { id: 4, fileName: 'Noite.jpg', uploader: 'Ameon.dg', uploadDate: '2024-04-10', thumbnail: 'images/montanhas.jpg', description: 'Foto de uma floresta densa durante a tarde.' }
];

// Função para carregar a lista de uploads na tela
function loadUploads() {
    const uploadsContainer = document.getElementById('uploads-container');
    uploadsContainer.innerHTML = '';  // Limpar conteúdo atual
    
    uploads.forEach(upload => {
        const uploadItem = document.createElement('div');
        uploadItem.classList.add('upload-item');
        
        const thumbnail = document.createElement('img');
        thumbnail.src = upload.thumbnail;
        thumbnail.alt = upload.fileName;
        thumbnail.classList.add('upload-thumbnail');
        
        const uploadDetails = document.createElement('div');
        uploadDetails.classList.add('upload-details');
        
        const uploaderName = document.createElement('h3');
        uploaderName.textContent = upload.uploader;
        
        const uploadDate = document.createElement('p');
        uploadDate.textContent = `Data: ${upload.uploadDate}`;
        
        const description = document.createElement('p');
        description.textContent = upload.description;

        // Botão para ver mais detalhes
        const viewButton = document.createElement('button');
        viewButton.textContent = 'Ver Tudo';
        viewButton.classList.add('view-button');
        viewButton.addEventListener('click', () => viewDetails(upload.id));  // Chama a função para mostrar os detalhes do upload
        
        uploadDetails.appendChild(uploaderName);
        uploadDetails.appendChild(uploadDate);
        uploadDetails.appendChild(description);
        uploadDetails.appendChild(viewButton);

        uploadItem.appendChild(thumbnail);
        uploadItem.appendChild(uploadDetails);

        uploadsContainer.appendChild(uploadItem);
    });
}

// Função para mostrar os detalhes de um upload
function viewDetails(uploadId) {
    const upload = uploads.find(u => u.id === uploadId);

    if (upload) {
        const detailsContainer = document.getElementById('details-container');
        const uploadDetails = document.getElementById('upload-details');
        
        // Limpar detalhes anteriores
        uploadDetails.innerHTML = '';
        
        // Carregar os detalhes do upload
        const img = document.createElement('img');
        img.src = upload.thumbnail;
        img.alt = upload.fileName;
        img.classList.add('upload-detail-thumbnail');

        const uploaderName = document.createElement('h2');
        uploaderName.textContent = `Uploader: ${upload.uploader}`;

        const uploadDate = document.createElement('p');
        uploadDate.textContent = `Data de Envio: ${upload.uploadDate}`;

        const description = document.createElement('p');
        description.textContent = `Descrição: ${upload.description}`;
        
        const fileLink = document.createElement('p');
        const downloadLink = document.createElement('a');
        downloadLink.href = `uploads/${upload.fileName}`;
        downloadLink.textContent = `Download da Foto (${upload.fileName})`;
        downloadLink.target = "_blank";
        fileLink.appendChild(downloadLink);

        uploadDetails.appendChild(img);
        uploadDetails.appendChild(uploaderName);
        uploadDetails.appendChild(uploadDate);
        uploadDetails.appendChild(description);
        uploadDetails.appendChild(fileLink);
        
        // Exibir a tela de detalhes e esconder a lista
        document.getElementById('uploads-container').style.display = 'none';
        detailsContainer.style.display = 'block';
    }
}

// Função para voltar à lista de uploads
document.getElementById('back-button').addEventListener('click', () => {
    // Esconder a tela de detalhes e mostrar a lista de uploads novamente
    document.getElementById('details-container').style.display = 'none';
    document.getElementById('uploads-container').style.display = 'block';
});

// Carregar a lista de uploads ao carregar a página
document.addEventListener('DOMContentLoaded', loadUploads);
