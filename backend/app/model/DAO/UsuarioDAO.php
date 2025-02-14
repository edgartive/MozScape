<?php

// Inclui o arquivo da classe Usuario
require_once __DIR__ . '/../../model/Usuario.php';
require_once __DIR__ . '/../../core/Database.php';

class UsuarioDAO
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Cria um novo usuário
    public function criarUsuario(Usuario $usuario)
    {
        $query = "INSERT INTO usuarios (nome_completo, email, senha, username, foto_de_perfil_url, frase_favorita, uploader, biografia, links) 
                  VALUES (:nome_completo, :email, :senha, :username, :foto_de_perfil_url, :frase_favorita, :uploader, :biografia, :links)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':nome_completo', $usuario->getNomeCompleto());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':senha', $usuario->getSenha());
        $stmt->bindValue(':username', $usuario->getUsername());
        $stmt->bindValue(':foto_de_perfil_url', $usuario->getFotoDePerfilUrl());
        $stmt->bindValue(':frase_favorita', $usuario->getFraseFavorita(), PDO::PARAM_INT);
        $stmt->bindValue(':uploader', $usuario->getUploader(), PDO::PARAM_INT);
        $stmt->bindValue(':biografia', $usuario->getBiografia());
        $stmt->bindValue(':links', $usuario->getLinks());

        return $stmt->execute();
    }


    // Busca um usuário pelo ID
    public function buscarUsuarioPorId($id_usuario)
    {
        $query = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Busca todos os usuários
    public function buscarTodosUsuarios()
    {
        $query = "SELECT * FROM usuarios";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Atualiza um usuário
    public function atualizarUsuario(Usuario $usuario)
    {
        $query = "UPDATE usuarios SET 
                  nome_completo = :nome_completo, 
                  email = :email, 
                  senha = :senha, 
                  username = :username, 
                  foto_de_perfil_url = :foto_de_perfil_url, 
                  frase_favorita = :frase_favorita, 
                  uploader = :uploader, 
                  biografia = :biografia, 
                  links = :links 
                  WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':nome_completo', $usuario->getNomeCompleto());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':senha', $usuario->getSenha());
        $stmt->bindValue(':username', $usuario->getUsername());
        $stmt->bindValue(':foto_de_perfil_url', $usuario->getFotoDePerfilUrl());
        $stmt->bindValue(':frase_favorita', $usuario->getFraseFavorita());
        $stmt->bindValue(':uploader', $usuario->getUploader(), PDO::PARAM_INT);
        $stmt->bindValue(':biografia', $usuario->getBiografia());
        $stmt->bindValue(':links', $usuario->getLinks());
        $stmt->bindValue(':id_usuario', $usuario->getIdUsuario(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Exclui um usuário
    public function excluirUsuario($id_usuario)
    {
        $query = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);

        return $stmt->execute();
    }
    public function buscarUsuarioPorUsername($username)
    {
        $query = "SELECT * FROM usuarios WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
