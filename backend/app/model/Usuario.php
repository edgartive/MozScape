<?php


class Usuario
{
    private $id_usuario;
    private $nome_completo;
    private $email;
    private $senha;
    private $username;
    private $foto_de_perfil_url;
    private $frase_favorita; #boolean 0u 0/1
    private $uploader;
    private $Biografia;
    private $links;

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getNomeCompleto()
    {
        return $this->nome_completo;
    }

    public function setNomeCompleto($nome_completo)
    {
        $this->nome_completo = $nome_completo;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function getBiografia()
    {
        return $this->Biografia;
    }

    public function setBiografia($Biografia)
    {
        $this->Biografia = $Biografia;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        // Encripta a senha antes de armazená-la
        $this->senha = $senha;
    }
    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getFotoDePerfilUrl()
    {
        return $this->foto_de_perfil_url;
    }

    public function setFotoDePerfilUrl($foto_de_perfil_url)
    {
        $this->foto_de_perfil_url = $foto_de_perfil_url;
    }

    public function getFraseFavorita()
    {
        return $this->frase_favorita;
    }

    public function setFraseFavorita($frase_favorita)
    {
        $this->frase_favorita = $frase_favorita;
    }

    public function getUploader()
    {
        return $this->uploader;
    }

    public function setUploader($uploader)
    {
        $this->uploader = $uploader;
    }


    public function getLinks()
    {
        return $this->links;
    }

    public function setLinks($links)
    {
        $this->links = $links;
    }
}
