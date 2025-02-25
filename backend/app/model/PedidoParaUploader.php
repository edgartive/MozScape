<?php

namespace App\Model;

class PedidoParaUploader
{
    private $id_pedido;
    private $id_usuario;
    private $foto_url;
    private $link_rede_social;
    private $frase_favorita;
    private $data_pedido;
    private $status;

    public function __construct($id_usuario, $foto_url, $link_rede_social, $frase_favorita, $data_pedido, $status = 'pendente')
    {
        $this->id_usuario = $id_usuario;
        $this->foto_url = $foto_url;
        $this->link_rede_social = $link_rede_social;
        $this->frase_favorita = $frase_favorita;
        $this->data_pedido = $data_pedido;
        $this->status = $status;
    }

    // Getters
    public function getIdPedido()
    {
        return $this->id_pedido;
    }

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function getFotoUrl()
    {
        return $this->foto_url;
    }

    public function getLinkRedeSocial()
    {
        return $this->link_rede_social;
    }

    public function getFraseFavorita()
    {
        return $this->frase_favorita;
    }

    public function getDataPedido()
    {
        return $this->data_pedido;
    }

    public function getStatus()
    {
        return $this->status;
    }

    // Setters
    public function setIdPedido($id_pedido)
    {
        $this->id_pedido = $id_pedido;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
