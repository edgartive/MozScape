<?php

namespace App\Model;

class Foto
{
    private $id_foto;
    private $caminho;
    private $resolucao;
    private $categoria;
    private $upload_id;

    // Getters
    public function getIdFoto()
    {
        return $this->id_foto;
    }

    public function getCaminho()
    {
        return $this->caminho;
    }

    public function getResolucao()
    {
        return $this->resolucao;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function getUploadId()
    {
        return $this->upload_id;
    }

    // Setters
    public function setIdFoto($id_foto)
    {
        $this->id_foto = $id_foto;
    }

    public function setCaminho($caminho)
    {
        $this->caminho = $caminho;
    }

    public function setResolucao($resolucao)
    {
        $this->resolucao = $resolucao;
    }

    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    public function setUploadId($upload_id)
    {
        $this->upload_id = $upload_id;
    }
}
