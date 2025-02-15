<?php
require_once __DIR__ . '/../model/dao/UsuarioDAO.php';
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../controller/UsuarioController.php';

session_start();
if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado!");
}

$id_usuario = $_SESSION['id_usuario'];

// Inicializa o controller
$db = new Database(); // Supondo que você já tenha uma classe Database configurada
$usuarioController = new UsuarioController($db);

// Busca o usuário atual
$usuario = $usuarioController->buscarUsuarioPorId($id_usuario);
$fotoAtual = $usuario['foto_de_perfil_url'] ?? 'default.jpg';

$erro = '';
$sucesso = '';

// Verifica se houve upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $uploadDir = __DIR__ . '/../uploads/profile_pics/';

    // Certifica-se de que a pasta existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Validação do arquivo
    $arquivo = $_FILES['foto'];
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
                header("Location: editarfotoperfil.php");
                exit();
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
            position: relative;
        }

        .edit-icon {
            position: absolute;
            top: 110px;
            left: 55%;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
        }

        .modal {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #000;
            text-align: center;
        }

        .modal.active {
            display: block;
        }

        #preview {
            display: none;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: 10px;
        }

        .mensagem {
            margin-top: 20px;
            color: red;
        }

        .sucesso {
            color: green;
        }
    </style>
</head>

<body>

    <div class="perfil-container">
        <div style="position: relative; display: inline-block;">
            <img src="../uploads/profile_pics/<?= htmlspecialchars($fotoAtual) ?>" class="foto-perfil" id="fotoPerfil">
            <i class="fas fa-pencil-alt edit-icon" id="editIcon"></i>
        </div>
        <?php if ($erro): ?>
            <div class="mensagem"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="mensagem sucesso"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>
    </div>

    <div id="modalEscolha" class="modal">
        <h3>O que deseja fazer?</h3>
        <button onclick="verFoto()">Ver Foto</button>
        <button onclick="trocarFoto()">Trocar Foto</button>
    </div>

    <div id="modalUpload" class="modal">
        <h3>Escolha uma nova foto</h3>
        <form action="editarfotoperfil.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="foto" id="inputFoto" accept="image/*">
            <img id="preview">
            <br>
            <button type="submit">Atualizar Foto</button>
            <button type="button" onclick="fecharModal('modalUpload')">Cancelar</button>
        </form>
    </div>

    <script>
        document.getElementById("editIcon").addEventListener("click", function() {
            document.getElementById("modalEscolha").classList.add("active");
        });

        function verFoto() {
            window.open(document.getElementById("fotoPerfil").src, "_blank");
        }

        function trocarFoto() {
            document.getElementById("modalEscolha").classList.remove("active");
            document.getElementById("modalUpload").classList.add("active");
        }

        function fecharModal(id) {
            document.getElementById(id).classList.remove("active");
        }

        document.getElementById("inputFoto").addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("preview").src = e.target.result;
                    document.getElementById("preview").style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

</body>

</html>