// Exibe ou oculta a caixa flutuante
function toggleCaixa() {
    const caixa = document.getElementById("infoCaixa");
    if (caixa.style.display === "none" || caixa.style.display === "") {
        caixa.style.display = "block";
    } else {
        caixa.style.display = "none";
    }
}
