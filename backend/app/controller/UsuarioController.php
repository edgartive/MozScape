<?php
class UsuarioController
{
    private $usuarioModel;

    public function __construct($usuarioModel)
    {
        $this->usuarioModel = $usuarioModel;
    }

    public function cadastrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verifica se a pasta de uploads existe
            $pastaFotos = 'uploads/fotos_perfil';
            if (!is_dir($pastaFotos)) {
                mkdir($pastaFotos, 0755, true);
            }

            // Processa o upload da foto de perfil
            $fotoPerfilUrl = null;
            if (isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] === UPLOAD_ERR_OK) {
                $nomeArquivo = uniqid() . '_' . basename($_FILES['foto_de_perfil']['name']); // Nome único para evitar conflitos
                $caminhoArquivo = $pastaFotos . '/' . $nomeArquivo;

                // Move o arquivo para a pasta de uploads
                if (move_uploaded_file($_FILES['foto_de_perfil']['tmp_name'], $caminhoArquivo)) {
                    $fotoPerfilUrl = $caminhoArquivo; // Salva o caminho relativo
                } else {
                    die("Erro ao fazer upload da foto de perfil.");
                }
            }

            // Prepara os dados do usuário
            $dadosUsuario = [
                'nome_completo' => $_POST['nome_completo'],
                'email' => $_POST['email'],
                'senha' => $_POST['senha'],
                'username' => $_POST['username'],
                'foto_de_perfil_url' => $fotoPerfilUrl,
                'frase_favorita' => $_POST['frase_favorita'],
                'uploader' => isset($_POST['uploader']) ? 1 : 0,
                'biografia' => $_POST['biografia'],
                'links' => $_POST['links']
            ];

            // Cadastra o usuário
            $this->usuarioModel->cadastrarUsuario($dadosUsuario);
            header('Location: listar.php'); // Redireciona para a listagem
        }
    }

    public function listar()
    {
        $usuarios = $this->usuarioModel->listarUsuarios();
        include 'View/usuarios/listar.php';
    }
}
