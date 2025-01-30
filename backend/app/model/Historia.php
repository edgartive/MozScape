<?php

namespace App\Model;

class Historia
{
    private $id_historia;
    private $upload_id;
    private $titulo;
    private $conteudo;

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

    // Getter and Setter for conteudo
    public function getConteudo()
    {
        return $this->conteudo;
    }

    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
    }
}
