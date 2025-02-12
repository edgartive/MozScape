<?php

class Upload
{
    private $id_upload;
    private $usuario_id; # FK - vem da tabela usuarios
    private $tipo;
    private $data_upload;
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


    public function getDataUpload()
    {
        return $this->data_upload;
    }

    public function setDataUpload($data_upload)
    {
        $this->data_upload = $data_upload;
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
