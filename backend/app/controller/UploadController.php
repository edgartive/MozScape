<?php

require_once __DIR__ . '/../model/Upload.php';
require_once __DIR__ . '/../model/dao/UploadDAO.php';

class UploadController
{
    private $uploadDAO;

    public function __construct($db)
    {
        $this->uploadDAO = new UploadDAO($db);
    }

    // Cria um novo upload
    public function criarUpload($usuario_id, $tipo, $data_upload, $descricao, $likes, $foto_url)
    {
        $upload = new Upload();
        $upload->setUsuarioId($usuario_id);
        $upload->setTipo($tipo);
        $upload->setDataUpload($data_upload);
        $upload->setDescricao($descricao);
        $upload->setLikes($likes);
        $upload->setFotoUrl($foto_url);

        return $this->uploadDAO->criarUpload($upload);
    }
}
