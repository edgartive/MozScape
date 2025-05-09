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

    public function removerUpload($id_upload)
    {
        $query = "DELETE FROM uploads WHERE id = :id_upload";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_upload', $id_upload, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function criarUpload(Upload $upload)
    {
        $query = "INSERT INTO UPLOADs (id_usuario, tipo, data_upload, descricao, likes, foto_url) 
                  VALUES (:usuario_id, :tipo, :data_upload, :descricao, :likes, :foto_url)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':usuario_id', $upload->getUsuarioId());
        $stmt->bindValue(':tipo', $upload->getTipo());
        $stmt->bindValue(':data_upload', $upload->getDataUpload());
        $stmt->bindValue(':descricao', $upload->getDescricao());
        $stmt->bindValue(':likes', $upload->getLikes());
        $stmt->bindValue(':foto_url', $upload->getFotoUrl());

        return $stmt->execute();
    }

    public function listarUploads()
    {
        $query = "SELECT * FROM UPLOADs ORDER BY data_upload DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function curtirUpload($id_upload)
    {
        $query = "UPDATE UPLOADs SET likes = likes + 1 WHERE id_upload = :id_upload";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_upload', $id_upload);
        $stmt->execute();

        $query = "SELECT likes FROM UPLOADs WHERE id_upload = :id_upload";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_upload', $id_upload);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['likes'];
    }
}
