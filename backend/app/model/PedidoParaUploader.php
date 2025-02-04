<?php

namespace App\Model;

class PedidoParaUploader
{
    private $id_pedido;
    private $id_usuario;
    private $foto1_url;
    private $foto2_url;
    private $foto3_url;
    private $frase_favorita;
    private $data_pedido;
    private $status;

    public function getIdPedido()
    {
        return $this->id_pedido;
    }

    public function setIdPedido($id_pedido)
    {
        $this->id_pedido = $id_pedido;
    }

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getFoto1Url()
    {
        return $this->foto1_url;
    }

    public function setFoto1Url($foto1_url)
    {
        $this->foto1_url = $foto1_url;
    }

    public function getFoto2Url()
    {
        return $this->foto2_url;
    }

    public function setFoto2Url($foto2_url)
    {
        $this->foto2_url = $foto2_url;
    }

    public function getFoto3Url()
    {
        return $this->foto3_url;
    }

    public function setFoto3Url($foto3_url)
    {
        $this->foto3_url = $foto3_url;
    }

    public function getFraseFavorita()
    {
        return $this->frase_favorita;
    }

    public function setFraseFavorita($frase_favorita)
    {
        $this->frase_favorita = $frase_favorita;
    }

    public function getDataPedido()
    {
        return $this->data_pedido;
    }

    public function setDataPedido($data_pedido)
    {
        $this->data_pedido = $data_pedido;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
