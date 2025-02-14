<?php
require_once __DIR__ . '/../model/dao/UsuarioDAO.php';
require_once __DIR__ . '/../model/Usuario.php';

session_start();
if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado!");
}

$id_usuario = $_SESSION['id_usuario'];
$usuarioDAO = new UsuarioDAO();
$usuario = $usuarioDAO->buscarUsuarioPorId($id_usuario);
$fotoAtual = $usuario['foto_de_perfil_url'] ?? 'default.jpg';

// Verifica se houve upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $uploadDir = __DIR__ . '/../uploads/profile_pics/';

    // Certifica-se de que a pasta existe

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $novoNome = uniqid() . '_' . basename($_FILES['foto']['name']);
    $caminhoFinal = $uploadDir . $novoNome;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoFinal)) {
        // Atualiza a URL no banco de dados
        $usuarioDAO->atualizarFotoPerfil($id_usuario, $novoNome);
        header("Location: editarfotoperfil.php");
        exit();
    } else {
        $erro = "Erro ao fazer upload da foto!";
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
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
    </style>
</head>

<body>

    <div class="perfil-container">
        <div style="position: relative; display: inline-block;">
            <img src="../uploads/profile_pics/<?= htmlspecialchars($fotoAtual) ?>" class="foto-perfil" id="fotoPerfil">
            <i class="fas fa-pencil-alt edit-icon" id="editIcon"></i>
        </div>
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