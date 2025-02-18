<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado. Faça login para continuar.");
}

// Caminhos corrigidos usando __DIR__
require_once __DIR__ . '/../../backend/app/controller/UsuarioController.php';
require_once __DIR__ . '/../../backend/app/core/Database.php';

$id_usuario = $_SESSION['id_usuario'];
$db = (new Database())->getConnection();
$usuarioController = new UsuarioController($db);

// Busca o usuário atual
$usuario = $usuarioController->buscarUsuarioPorId($id_usuario);
$fotoAtual = $usuario['foto_de_perfil_url'] ?? 'default.jpg';

$erro = '';
$sucesso = '';

// Verifica se houve upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_de_perfil'])) {
    $uploadDir = __DIR__ . '/../uploads/profile_pics/';

    // Certifica-se de que a pasta existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Validação do arquivo
    $arquivo = $_FILES['foto_de_perfil'];
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($extensao, $extensoesPermitidas)) {
        $erro = "Formato de arquivo não permitido! Apenas JPG, JPEG, PNG e GIF são aceitos.";
    } elseif ($arquivo['size'] > 5 * 1024 * 1024) { // 5MB
        $erro = "O arquivo é muito grande! O tamanho máximo permitido é 5MB.";
    } else {
        // Exclui a foto anterior, se não for a padrão
        if ($fotoAtual !== 'default.jpg' && file_exists($uploadDir . $fotoAtual)) {
            unlink($uploadDir . $fotoAtual);
        }

        // Gera um nome único para o arquivo
        $novoNome = uniqid() . '_' . basename($arquivo['name']);
        $caminhoFinal = $uploadDir . $novoNome;

        if (move_uploaded_file($arquivo['tmp_name'], $caminhoFinal)) {
            // Atualiza a URL no banco de dados
            if ($usuarioController->atualizarFotoPerfil($id_usuario, $novoNome)) {
                $sucesso = "Foto de perfil atualizada com sucesso!";
                header("Location: visaoperfil.php");
                exit;
            } else {
                $erro = "Erro ao atualizar a foto de perfil no banco de dados.";
            }
        } else {
            $erro = "Erro ao fazer upload da foto!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Foto de Perfil</title>
    <link rel="stylesheet" href="styles.css"> <!-- Seu CSS aqui -->
    <style>
        .perfil-container {
            text-align: center;
            margin-top: 50px;
        }

        .foto-perfil {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="file"] {
            display: block;
            margin: 0 auto;
        }

        .mensagem {
            margin-top: 20px;
            color: red;
        }

        .sucesso {
            color: green;
        }

        .btn-salvar {
            background-color: #4CAF50;
            /* Verde */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-salvar:hover {
            background-color: #45a049;
            /* Verde mais escuro ao passar o mouse */
        }
    </style>
</head>

<body>

    <div class="perfil-container">
        <img src="../uploads/profile_pics/<?= htmlspecialchars($fotoAtual) ?>" class="foto-perfil" id="fotoPerfil">

        <?php if ($erro): ?>
            <div class="mensagem"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="mensagem sucesso"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form action="editarfotoperfil.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="foto_de_perfil">Escolha uma nova foto de perfil:</label>
                <input type="file" name="foto_de_perfil" id="foto_de_perfil" accept="image/*" required>
            </div>
            <button type="submit" class="btn-salvar">Salvar</button>
        </form>
    </div>

</body>

</html>