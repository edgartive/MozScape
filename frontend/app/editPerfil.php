<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php'); // Redireciona para o login se não estiver logado
    exit;
}
require_once __DIR__ . '/../backend/app/controller/UsuarioController.php';
require_once __DIR__ . '/../backend/app/core/Database.php';
// Conecta ao banco de dados
$db = (new Database())->getConnection();
$usuarioController = new UsuarioController($db);

// Busca os dados do usuário logado
$usuario = $usuarioController->buscarUsuarioPorId($_SESSION['id_usuario']);

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $nome_completo = $_POST['nome_completo'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $frase_favorita = $_POST['frase_favorita'];
    $biografia = $_POST['biografia'];
    $links = $_POST['links'];

    // Processa o upload da nova foto de perfil, se houver
    // Processa o upload da nova foto de perfil, se houver
    if (isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] === UPLOAD_ERR_OK) {
        $foto_de_perfil = $_FILES['foto_de_perfil'];
        $nome_arquivo = uniqid() . '_' . basename($foto_de_perfil['name']);
        $caminho_destino = __DIR__ . '/../../uploads/profile_pics/' . $nome_arquivo;
        $nova_url = 'https://seusite.com/uploads/profile_pics/' . $nome_arquivo; // URL gerada para salvar no banco

        // Se houver uma URL antiga e ela for do seu domínio, remova-a
        if (!empty($usuario['foto_de_perfil_url']) && strpos($usuario['foto_de_perfil_url'], 'seusite.com/uploads/profile_pics/') !== false) {
            $foto_antiga = __DIR__ . '/../../uploads/profile_pics/' . basename($usuario['foto_de_perfil_url']);
            if (file_exists($foto_antiga)) {
                unlink($foto_antiga);
            }
        }

        // Move o arquivo para a pasta de uploads
        if (move_uploaded_file($foto_de_perfil['tmp_name'], $caminho_destino)) {
            $foto_de_perfil_url = $nova_url; // Atualiza a URL da foto de perfil
        } else {
            die("Erro ao fazer upload da foto de perfil.");
        }
    } else {
        $foto_de_perfil_url = $usuario['foto_de_perfil_url']; // Mantém a foto atual caso não haja novo upload
    }

    // Atualiza os dados do usuário
    $usuarioController->atualizarUsuario(
        $_SESSION['id_usuario'],
        $nome_completo,
        $email,
        $usuario['senha'], // Mantém a senha atual
        $username,
        $foto_de_perfil_url, // Salva a nova URL no banco de dados
        $frase_favorita,
        $usuario['uploader'],
        $biografia,
        $links
    );


    // Redireciona para a página de perfil
    header('Location: visaoPerfil.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="perfil.css">
    <title>Editar Perfil</title>
</head>

<body>
    <div class="profile-container">
        <div class="profile-header">
            <!-- Exibe a foto de perfil atual -->
            <div class="profile-picture-container">
                <img src="../../uploads/profile_pics/<?php echo $usuario['foto_de_perfil_url']; ?>" alt="Foto de Perfil" class="profile-picture" id="profile-picture-preview">
                <label for="foto_de_perfil" class="upload-label">Alterar Foto</label>
                <input type="file" id="foto_de_perfil" name="foto_de_perfil" accept="image/*" style="display: none;">
            </div>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nome_completo">Nome Completo:</label>
                <input type="text" id="nome_completo" name="nome_completo" value="<?php echo htmlspecialchars($usuario['nome_completo']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($usuario['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="frase_favorita">Frase Favorita:</label>
                <input type="text" id="frase_favorita" name="frase_favorita" value="<?php echo htmlspecialchars($usuario['frase_favorita']); ?>">
            </div>
            <div class="form-group">
                <label for="biografia">Biografia:</label>
                <textarea id="biografia" name="biografia"><?php echo htmlspecialchars($usuario['biografia']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="links">Links:</label>
                <textarea id="links" name="links"><?php echo htmlspecialchars($usuario['links']); ?></textarea>
            </div>
            <button type="submit" class="save-button">Salvar Alterações</button>
        </form>
    </div>

    <script>
        // Script para mostrar a prévia da foto de perfil
        document.getElementById('foto_de_perfil').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-picture-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>