<?php

require_once __DIR__ . '/../model/dao/UsuarioDAO.php';
require_once __DIR__ . '/../model/Usuario.php';

class UsuarioController
{
    private $usuarioDAO;

    public function __construct($db)
    {
        $this->usuarioDAO = new UsuarioDAO($db);
    }

    // Cria um novo usuário
    public function criarUsuario($nome_completo, $email, $senha, $username, $foto_de_perfil_url, $frase_favorita, $uploader, $biografia, $links)
    {
        $usuario = new Usuario();
        $usuario->setNomeCompleto($nome_completo);
        $usuario->setEmail($email);
        $usuario->setSenha($senha);
        $usuario->setUsername($username);
        $usuario->setFotoDePerfilUrl($foto_de_perfil_url);
        $usuario->setFraseFavorita($frase_favorita);
        $usuario->setUploader($uploader);
        $usuario->setBiografia($biografia);
        $usuario->setLinks($links);

        return $this->usuarioDAO->criarUsuario($usuario);
    }

    // Busca um usuário pelo ID
    public function buscarUsuarioPorId($id_usuario)
    {
        return $this->usuarioDAO->buscarUsuarioPorId($id_usuario);
    }

    // Busca todos os usuários
    public function buscarTodosUsuarios()
    {
        return $this->usuarioDAO->buscarTodosUsuarios();
    }

    // Atualiza um usuário
    public function atualizarUsuario($id_usuario, $nome_completo, $email, $senha, $username, $foto_de_perfil_url, $frase_favorita, $uploader, $biografia, $links)
    {
        $usuario = new Usuario();
        $usuario->setIdUsuario($id_usuario);
        $usuario->setNomeCompleto($nome_completo);
        $usuario->setEmail($email);
        $usuario->setSenha($senha);
        $usuario->setUsername($username);
        $usuario->setFotoDePerfilUrl($foto_de_perfil_url);
        $usuario->setFraseFavorita($frase_favorita);
        $usuario->setUploader($uploader);
        $usuario->setBiografia($biografia);
        $usuario->setLinks($links);

        return $this->usuarioDAO->atualizarUsuario($usuario);
    }

    // Exclui um usuário
    public function excluirUsuario($id_usuario)
    {
        return $this->usuarioDAO->excluirUsuario($id_usuario);
    }
    // Busca um usuário pelo username
    public function buscarUsuarioPorUsername($username)
    {
        return $this->usuarioDAO->buscarUsuarioPorUsername($username);
    }
}
