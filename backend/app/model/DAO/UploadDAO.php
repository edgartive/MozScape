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
        $query = "DELETE FROM uploads WHERE id_upload = :id_upload";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_upload', $id_upload, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function buscarUploadPorId($id_upload)
    {
        $query = "SELECT u.*, us.nome_completo as autor_nome 
                  FROM uploads u
                  JOIN usuarios us ON u.id_usuario  = us.id_usuario
                  WHERE u.id_upload = :id_upload";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_upload', $id_upload, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
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
    public function removerLike($id_upload, $id_usuario)
    {
        $this->db->beginTransaction();

        try {
            // Remove o registro de like
            $stmt = $this->db->prepare("DELETE FROM upload_likes WHERE id_upload = ? AND id_usuario = ?");
            $stmt->execute([$id_upload, $id_usuario]);

            // Atualiza o contador de likes
            $stmt = $this->db->prepare("UPDATE uploads SET likes = GREATEST(likes - 1, 0) WHERE id_upload = ?");
            $stmt->execute([$id_upload]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao remover like: " . $e->getMessage());
            return false;
        }
    }
    public function listarUploads()
    {
        $query = "SELECT * FROM UPLOADs ORDER BY data_upload DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarUploadsPorIDUsuario($id_usuario)
    {
        $query = "SELECT * FROM UPLOADs  WHERE id_usuario = $id_usuario ORDER BY data_upload DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function usuarioJaCurtiu($id_upload, $id_usuario)
    {
        try {
            $query = "SELECT COUNT(*) as total FROM upload_likes 
                     WHERE id_upload = :id_upload AND id_usuario = :id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_upload', $id_upload, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['total'] > 0);
        } catch (PDOException $e) {
            error_log("Erro ao verificar like: " . $e->getMessage());
            return false;
        }
    }
    public function curtirUpload($id_upload, $id_usuario)
    {
        $this->db->beginTransaction();

        try {
            // 1. Verifica se já curtiu
            $check = $this->db->prepare("SELECT id FROM upload_likes WHERE id_upload = ? AND id_usuario = ?");
            $check->execute([$id_upload, $id_usuario]);

            if ($check->rowCount() > 0) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Você já curtiu este post'];
            }

            // 2. Adiciona o registro de like
            $insert = $this->db->prepare("INSERT INTO upload_likes (id_upload, id_usuario) VALUES (?, ?)");
            $insert->execute([$id_upload, $id_usuario]);

            // 3. Atualiza o contador
            $update = $this->db->prepare("UPDATE uploads SET likes = likes + 1 WHERE id_upload = ?");
            $update->execute([$id_upload]);

            // 4. Pega o novo total
            $total = $this->db->prepare("SELECT likes FROM uploads WHERE id_upload = ?");
            $total->execute([$id_upload]);
            $novoTotal = $total->fetchColumn();

            $this->db->commit();
            return ['success' => true, 'likes' => $novoTotal];
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao curtir: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erro ao processar like'];
        }
    }
    public function usuarioJaCurti1($id_upload, $usuario_id)
    {
        $sql = "SELECT COUNT(*) FROM upload_likes WHERE id_upload = :id_upload AND id_usuario = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_upload', $id_upload, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }


    public function curtirUpload1($id_upload)
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
    public function buscarFotografoComMaisUploads()
    {
        $query = "SELECT 
                    u.id_usuario, 
                    u.nome_completo, 
                    u.foto_de_perfil_url,
                    COUNT(up.id_upload) as total_uploads
                  FROM UPLOADs up
                  JOIN usuarios u ON up.id_usuario = u.id_usuario
                  WHERE u.uploader = 1
                  GROUP BY up.id_usuario
                  ORDER BY total_uploads DESC
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarFotoComMaisLikes()
    {
        $query = "SELECT 
                    u.*, 
                    us.nome_completo as autor_nome,
                    us.foto_de_perfil_url as autor_foto
                  FROM UPLOADs u
                  JOIN usuarios us ON u.id_usuario = us.id_usuario
                  ORDER BY u.likes DESC
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarFotoMaisBaixada()
    {
        $query = "SELECT 
                    u.*, 
                    us.nome_completo as autor_nome,
                    us.foto_de_perfil_url as autor_foto,
                    COUNT(d.id_download) as downloads
                  FROM UPLOADs u
                  JOIN usuarios us ON u.id_usuario = us.id_usuario
                  LEFT JOIN downloads d ON u.id_upload = d.id_upload
                  GROUP BY u.id_upload
                  ORDER BY downloads DESC
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function adicionarLike($id_upload, $id_usuario)
    {
        $this->db->beginTransaction();

        try {
            // 1. Registra o like
            $stmt = $this->db->prepare("INSERT INTO upload_likes (id_upload, id_usuario) VALUES (?, ?)");
            $stmt->execute([$id_upload, $id_usuario]);

            // 2. Atualiza o contador
            $stmt = $this->db->prepare("UPDATE uploads SET likes = likes + 1 WHERE id_upload = ?");
            $stmt->execute([$id_upload]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao adicionar like: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTotalLikes($id_upload)
    {
        $stmt = $this->db->prepare("SELECT likes FROM uploads WHERE id_upload = ?");
        $stmt->execute([$id_upload]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['likes'] : 0;
    }

    public function buscarTopFotografos($limit = 5)
    {
        $query = "SELECT 
                    u.id_usuario, 
                    u.nome_completo, 
                    u.foto_de_perfil_url,
                    COUNT(up.id_upload) as total_uploads
                  FROM UPLOADs up
                  JOIN usuarios u ON up.id_usuario = u.id_usuario
                  WHERE u.uploader = 1
                  GROUP BY up.id_usuario
                  ORDER BY total_uploads DESC
                  LIMIT :limit";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
