let currentPanel = 0; // Índice do painel atual
const panels = document.querySelectorAll('.manual-panel');
const slider = document.querySelector('.manual-slider');

function updateSlider() {
    const panelWidth = panels[0].clientWidth;
    slider.style.transform = `translateX(-${currentPanel * panelWidth}px)`;
}

function nextPanel() {
    if (currentPanel < panels.length - 1) {
        currentPanel++;
        updateSlider();
    }
}

function prevPanel() {
    if (currentPanel > 0) {
        currentPanel--;
        updateSlider();
    }
}

// Ajustar a posição do slider ao redimensionar a janela
window.addEventListener('resize', updateSlider);
