<?php

require_once __DIR__ . '/../../model/Upload.php';
require_once __DIR__ . '/../../core/Database.php';

class UploadDAO
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Cria um novo upload
    public function criarUpload(Upload $upload)
    {
        $query = "INSERT INTO UPLOAD (usuario_id, tipo, data_upload, descricao, likes, foto_url) 
                  VALUES (:usuario_id, :tipo, :data_upload, :descricao, :likes, :foto_url)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':usuario_id', $upload->getUsuarioId());
        $stmt->bindValue(':tipo', $upload->getTipo());
        $stmt->bindValue(':data_upload', $upload->getDataUpload());
        $stmt->bindValue(':descricao', $upload->getDescricao());
        $stmt->bindValue(':likes', $upload->getLikes(), PDO::PARAM_INT);
        $stmt->bindValue(':foto_url', $upload->getFotoUrl());

        return $stmt->execute();
    }
}
