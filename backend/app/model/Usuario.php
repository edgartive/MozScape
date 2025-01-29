<?php

namespace App\Model;

class Usuario
{
    private $id_usuario;
    private $nome_completo;
    private $email;
    private $senha;
    private $username;
    private $foto_de_perfil;
    private $frase_favorita;
    private $uploader;
    private $cidade;
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

    public function getFotoDePerfil()
    {
        return $this->foto_de_perfil;
    }

    public function setFotoDePerfil($foto_de_perfil)
    {
        $this->foto_de_perfil = $foto_de_perfil;
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

    public function getCidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
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
