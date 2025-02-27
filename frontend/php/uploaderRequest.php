<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado. Faça login para continuar.");
}

require_once __DIR__ . '/../../backend/app/controller/PedidoParaUploaderController.php';
require_once __DIR__ . '/../../backend/app/core/Database.php';

$id_usuario = $_SESSION['id_usuario'];
$db = (new Database())->getConnection();
$pedidoUploaderController = new PedidoUploaderController($db);

// Verifica se o usuário já é um uploader
$query = "SELECT uploader FROM usuarios WHERE id_usuario = :id_usuario";
$stmt = $db->prepare($query);
$stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$uploader = $stmt->fetchColumn();

// Verifica se o usuário já tem um pedido
$pedidoExistente = $pedidoUploaderController->verificarPedidoExistente($id_usuario);
$statusPedido = $pedidoUploaderController->buscarStatusPedido($id_usuario); // Busca o status do pedido
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/request-uploader.css">
    <title>Solicitar Tornar-se Uploader</title>
</head>

<body>
    <div class="form-container">
        <h1>Solicitar Tornar-se um Uploader</h1>

        <?php if ($uploader == 1): ?>
            <!-- Se o usuário já for um uploader -->
            <div class="status-pedido">
                <p>Você já é um uploader. Aproveite para enviar suas fotos!</p>
                <a href="upload.php" class="submit-button">Ir para a tela de upload de fotos</a>
            </div>
        <?php elseif ($pedidoExistente): ?>
            <!-- Exibe o status do pedido se já houver um pedido -->
            <div class="status-pedido">
                <p>Status do seu pedido: <strong><?= htmlspecialchars($statusPedido) ?></strong></p>
                <?php if ($statusPedido === 'aprovado'): ?>
                    <p>Parabéns! Seu pedido foi aprovado. Agora você é um uploader.</p>
                    <a href="upload.php" class="submit-button">Ir para a tela de upload de fotos</a>
                <?php elseif ($statusPedido === 'recusado'): ?>
                    <p>Seu pedido foi recusado. Entre em contato com o suporte para mais informações.</p>
                <?php else: ?>
                    <p>Seu pedido está pendente. Aguarde a aprovação.</p>
                <?php endif; ?>
                <p><a href="../index.php">Voltar para a página inicial</a></p>
            </div>
        <?php else: ?>
            <!-- Exibe o formulário se não houver pedido -->
            <form id="uploaderRequestForm" action="processos/processar_pedido.php" method="POST" enctype="multipart/form-data">
                <!-- Motivação -->
                <div class="form-group">
                    <label for="motivation">Sua Motivação para se Tornar um Fotógrafo</label>
                    <textarea id="motivation" name="motivation" rows="4" placeholder="Descreva sua motivação..." required></textarea>
                </div>

                <!-- Frase Favorita -->
                <div class="form-group">
                    <label for="favoritePhrase">Sua Frase Favorita</label>
                    <input type="text" id="favoritePhrase" name="favoritePhrase" placeholder="Digite sua frase favorita..." required>
                </div>

                <!-- Upload de uma foto -->
                <div class="form-group">
                    <label for="photoUpload">Faça o Upload de uma das suas fotos</label>
                    <input type="file" id="photoUpload" name="photoUpload" accept="image/*" required>
                    <small>Por favor, faça o upload de uma foto (JPG, PNG).</small>
                </div>

                <!-- Link da Rede Social -->
                <div class="form-group">
                    <label for="socialLink">Link da Rede Social onde a foto foi postada</label>
                    <input type="url" id="socialLink" name="socialLink" placeholder="Digite o link da rede social..." required>
                </div>

                <!-- ID do Usuário (oculto) -->
                <input type="hidden" name="id_usuario" value="<?= $_SESSION['id_usuario'] ?>" />

                <!-- Termos e Condições -->
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">Eu concordo com os termos e condições para me tornar um uploader.</label>
                </div>
                <small>Ao concordar, você reconhece que seus envios serão avaliados e poderão ser exibidos publicamente.</small>

                <div class="form-group checkbox-group">
                    <input type="checkbox" id="authorship" name="authorship" required>
                    <label for="authorship">Eu confirmo que a foto enviada é de minha autoria.</label>
                </div>

                <!-- Botão de Enviar -->
                <button type="submit" class="submit-button">Enviar Solicitação</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>