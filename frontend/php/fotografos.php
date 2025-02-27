<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado como administrador
if (!isset($_SESSION['id_admin']) || $_SESSION['tipo_usuario'] !== 'admin') {
    die("Acesso negado. Você não tem permissão para acessar esta página.");
}

require_once __DIR__ . '/../../backend/app/core/Database.php';
require_once __DIR__ . '/../../backend/app/controller/UsuarioController.php';

$db = (new Database())->getConnection();
$usuarioController = new UsuarioController($db);

// Busca todos os fotógrafos (usuários com uploader = 1)
$fotografos = $usuarioController->buscarFotografos();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Fotógrafos</title>
    <link rel="stylesheet" href="../css/upload.css"> <!-- Seu CSS aqui -->
    <style>
        :root {
            --primary-color: rgb(0, 155, 225);
            /* Verde */
            --secondary-color: #333;
            /* Preto suave */
            --background-color: #0a0f25;
            /* Azul escuro */
            --text-color: #ffffff;
            /* Cor do texto */
            --hover-color: rgb(0, 155, 225);
            /* Verde claro */
        }

        body {

            padding: 20px;
        }

        h1 {
            color: white;
            text-align: center;
            margin-bottom: 20px;
        }

        .fotografo-container {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: rgba(141, 190, 255, 0.14);
            display: flex;
            align-items: center;
        }

        .fotografo-container img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .fotografo-container .info {
            flex: 1;
        }

        .fotografo-container .info p {
            margin: 5px 0;
        }

        .fotografo-container .info p strong {
            font-weight: bold;
        }

        .fotografo-container .info a {
            color: #2196f3;
            text-decoration: none;
        }

        .fotografo-container .info a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>Lista de Fotógrafos</h1>

    <?php if (empty($fotografos)): ?>
        <p>Nenhum fotógrafo encontrado.</p>
    <?php else: ?>
        <?php foreach ($fotografos as $fotografo): ?>
            <div class="fotografo-container">
                <!-- Foto de perfil -->
                <img src="../uploads/profile_pics/<?= htmlspecialchars($fotografo['foto_de_perfil_url']) ?>" alt="Foto de perfil">

                <!-- Informações do fotógrafo -->
                <div class="info">
                    <p><strong>Nome:</strong> <?= htmlspecialchars($fotografo['nome_completo']) ?></p>
                    <p><strong>Username:</strong> <?= htmlspecialchars($fotografo['username']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($fotografo['email']) ?></p>
                    <p><strong>Biografia:</strong> <?= htmlspecialchars($fotografo['biografia'] ?? 'N/A') ?></p>
                    <p><strong>Links:</strong>
                        <?php if (!empty($fotografo['links'])): ?>
                            <a href="<?= htmlspecialchars($fotografo['links']) ?>" target="_blank"><?= htmlspecialchars($fotografo['links']) ?></a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>