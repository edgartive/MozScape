<?php

class Upload
{
    private $id_upload;
    private $usuario_id;
    private $tipo;
    private $categoria_id;
    private $data_upload;
    private $status;
    private $descricao;
    private $likes;


    public function getIdUpload()
    {
        return $this->id_upload;
    }

    public function setIdUpload($id_upload)
    {
        $this->id_upload = $id_upload;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getCategoriaId()
    {
        return $this->categoria_id;
    }

    public function setCategoriaId($categoria_id)
    {
        $this->categoria_id = $categoria_id;
    }

    public function getDataUpload()
    {
        return $this->data_upload;
    }

    public function setDataUpload($data_upload)
    {
        $this->data_upload = $data_upload;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getLikes()
    {
        return $this->likes;
    }

    public function setLikes($likes)
    {
        $this->likes = $likes;
    }
}
