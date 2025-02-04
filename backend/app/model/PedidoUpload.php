<?php

namespace App\Model;

class PedidoUpload
{
    private $id_pedido;
    private $id_uploader;
    private $foto_url; // URL da foto enviada
    private $descricao;
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

    public function getIdUploader()
    {
        return $this->id_uploader;
    }

    public function setIdUploader($id_uploader)
    {
        $this->id_uploader = $id_uploader;
    }

    public function getFotoUrl()
    {
        return $this->foto_url;
    }

    public function setFotoUrl($foto_url)
    {
        $this->foto_url = $foto_url;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
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
