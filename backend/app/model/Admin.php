<?php

namespace App\Model;

class Admin
{
    private $id_admin;
    private $nome;
    private $email;
    private $senha;

    public function __construct($id_admin, $nome, $email, $senha)
    {
        $this->id_admin = $id_admin;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
    }

    public function getIdAdmin()
    {
        return $this->id_admin;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setIdAdmin($id_admin)
    {
        $this->id_admin = $id_admin;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }
}
