<?php

namespace App\Model;

class Historia
{
    private $id_historia;
    private $upload_id;
    private $titulo;
    private $id_usuario; # FK - vem da tabela usuarios
    private $info_wiki; # toda historia do lugar

    // Getter and Setter for id_usuario
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    // Getter and Setter for info_wiki
    public function getInfoWiki()
    {
        return $this->info_wiki;
    }

    public function setInfoWiki($info_wiki)
    {
        $this->info_wiki = $info_wiki;
    }

    // Getter and Setter for id_historia
    public function getIdHistoria()
    {
        return $this->id_historia;
    }

    public function setIdHistoria($id_historia)
    {
        $this->id_historia = $id_historia;
    }

    // Getter and Setter for upload_id
    public function getUploadId()
    {
        return $this->upload_id;
    }

    public function setUploadId($upload_id)
    {
        $this->upload_id = $upload_id;
    }

    // Getter and Setter for titulo
    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }
}
