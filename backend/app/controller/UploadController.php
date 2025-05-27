<?php

require_once __DIR__ . '/../model/DAO/UploadDAO.php';
require_once __DIR__ . '/../model/DAO/PedidoUploadDAO.php';
require_once __DIR__ . '/../model/Upload.php';

class UploadController
{
    private $uploadDAO;
    private $db;
    private $pedidoUploadDAO;
    public function usuarioJaCurtiu1($id_upload, $usuario_id)
    {
        return $this->uploadDAO->usuarioJaCurtiu($id_upload, $usuario_id);
    }
    public function pesquisarPorCategoria($categoria, $termo)
    {
        $sql = "SELECT * FROM uploads WHERE categoria = :categoria AND (descricao LIKE :termo OR tags LIKE :termo)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':categoria', $categoria);
        $stmt->bindValue(':termo', '%' . $termo . '%');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function usuarioJaCurtiu($id_upload, $id_usuario)
    {
        return $this->uploadDAO->usuarioJaCurtiu($id_upload, $id_usuario);
    }
    public function curtirUpload($id_upload)
    {
        if (!isset($_SESSION['id_usuario'])) {
            return json_encode(['success' => false, 'message' => 'Não logado']);
        }

        $id_usuario = $_SESSION['id_usuario'];
        $result = $this->uploadDAO->curtirUpload($id_upload, $id_usuario);

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
    public function __construct($db)
    {
        $this->db = $db;
        $this->uploadDAO = new UploadDAO($db);
        $this->pedidoUploadDAO = new PedidoUploadDAO($db);
    }
    public function buscarUploadPorId($id_upload)
    {
        return $this->uploadDAO->buscarUploadPorId($id_upload);
    }
    public function removerLike($id_upload)
    {
        if (!isset($_SESSION['id_usuario'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Não logado']);
            exit;
        }

        $id_usuario = $_SESSION['id_usuario'];
        $result = $this->uploadDAO->removerLike($id_upload, $id_usuario);

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
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
    public function listarUploadsPorUsuario($id_usuario)
    {
        $query = "SELECT * FROM uploads WHERE id_usuario = ? ORDER BY data_upload DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarUploads()
    {
        return $this->uploadDAO->listarUploads();
    }
    public function listarUploadsPorIDUsuario($id_usuario)
    {
        return $this->uploadDAO->listarUploadsPorIDUsuario($id_usuario);
    }
    public function criarUploadAprovadoTeste($id_pedido)
    {
        // Buscar o pedido
        $pedido = $this->pedidoUploadDAO->buscarPedidoPorId($id_pedido);
        if (!$pedido || $pedido['status'] !== 'pendente') {
            return false;
        }

        // Criar registro na tabela UPLOAD
        $data_upload = date('Y-m-d H:i:s');
        $upload = new Upload();
        $upload->setUsuarioId($pedido['id_usuario']);
        $upload->setTipo($pedido['tipo']);
        $upload->setDataUpload($data_upload);
        $upload->setDescricao($pedido['descricao']);
        $upload->setLikes(0);
        $upload->setFotoUrl($pedido['foto_url']);

        if ($this->uploadDAO->criarUpload($upload)) {
            // Atualizar status do pedido
            return $this->pedidoUploadDAO->atualizarStatus($id_pedido, 'aprovado');
        }
        return false;
    }
    public function curtirUpload2($id_upload)
    {
        if (!isset($_SESSION['id_usuario'])) {
            return false;
        }

        $id_usuario = $_SESSION['id_usuario'];
        return $this->uploadDAO->curtirUpload($id_upload, $id_usuario);
    }
    public function buscarUploadPorId3($id_upload)
    {
        $query = "SELECT u.*, us.nome_completo as autor_nome 
                  FROM uploads u
                  JOIN usuarios us ON u.usuario_id = us.id_usuario
                  WHERE u.id = :id_upload";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_upload', $id_upload, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function pesquisarUploads($termo)
    {
        $query = "SELECT u.* FROM UPLOADs u
              JOIN usuarios us ON u.id_usuario = us.id_usuario
              WHERE u.descricao LIKE :termo 
              OR u.tipo LIKE :termo
              OR us.nome_completo LIKE :termo
              ORDER BY u.data_upload DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':termo', '%' . $termo . '%');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function pesquisarPorFotografo($nome)
    {
        $query = "SELECT u.* FROM UPLOAD u
              JOIN usuarios us ON u.usuario_id = us.id_usuario
              WHERE us.nome_completo LIKE :nome
              ORDER BY u.data_upload DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%' . $nome . '%');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarUploadsPorCategoria($categoria)
    {
        $query = "SELECT * FROM UPLOADs WHERE tipo = :categoria ORDER BY data_upload DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':categoria', $categoria);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarFotografoComMaisUploads()
    {
        return $this->uploadDAO->buscarFotografoComMaisUploads();
    }

    public function buscarFotoComMaisLikes()
    {
        return $this->uploadDAO->buscarFotoComMaisLikes();
    }

    public function buscarTopFotografos($limit = 5)
    {
        return $this->uploadDAO->buscarTopFotografos($limit);
    }


    // Adicione estes métodos ao seu UploadController existente

    /**
     * Lista uploads com paginação
     */
    public function listarUploadsComPaginacao($offset, $limite)
    {
        $query = "SELECT * FROM uploads ORDER BY data_upload DESC LIMIT :limite OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Conta total de uploads
     */
    public function contarTodosUploads()
    {
        $query = "SELECT COUNT(*) as total FROM uploads";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Lista uploads por categoria com paginação
     */
    public function listarUploadsPorCategoriaComPaginacao($categoria, $offset, $limite)
    {
        $query = "SELECT * FROM uploads WHERE categoria = :categoria ORDER BY data_upload DESC LIMIT :limite OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Conta uploads por categoria
     */
    public function contarUploadsPorCategoria($categoria)
    {
        $query = "SELECT COUNT(*) as total FROM uploads WHERE categoria = :categoria";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Pesquisa uploads com paginação
     */
    public function pesquisarUploadsComPaginacao($termo, $offset, $limite)
    {
        $query = "SELECT * FROM uploads WHERE (descricao LIKE :termo OR categoria LIKE :termo) ORDER BY data_upload DESC LIMIT :limite OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $termo_like = '%' . $termo . '%';
        $stmt->bindParam(':termo', $termo_like);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Conta uploads da pesquisa
     */
    public function contarUploads($termo)
    {
        $query = "SELECT COUNT(*) as total FROM uploads WHERE (descricao LIKE :termo OR categoria LIKE :termo)";
        $stmt = $this->db->prepare($query);
        $termo_like = '%' . $termo . '%';
        $stmt->bindParam(':termo', $termo_like);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Pesquisa por fotógrafo com paginação
     */
    public function pesquisarPorFotografoComPaginacao($termo, $offset, $limite)
    {
        $query = "SELECT u.* FROM uploads u 
              INNER JOIN usuarios usr ON u.id_usuario = usr.id_usuario 
              WHERE usr.nome_completo LIKE :termo 
              ORDER BY u.data_upload DESC LIMIT :limite OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $termo_like = '%' . $termo . '%';
        $stmt->bindParam(':termo', $termo_like);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Conta uploads por fotógrafo
     */
    public function contarPorFotografo($termo)
    {
        $query = "SELECT COUNT(*) as total FROM uploads u 
              INNER JOIN usuarios usr ON u.id_usuario = usr.id_usuario 
              WHERE usr.nome_completo LIKE :termo";
        $stmt = $this->db->prepare($query);
        $termo_like = '%' . $termo . '%';
        $stmt->bindParam(':termo', $termo_like);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Pesquisa por categoria com termo e paginação
     */
    public function pesquisarPorCategoriaComPaginacao($categoria, $termo, $offset, $limite)
    {
        $query = "SELECT * FROM uploads WHERE categoria = :categoria AND descricao LIKE :termo ORDER BY data_upload DESC LIMIT :limite OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':categoria', $categoria);
        $termo_like = '%' . $termo . '%';
        $stmt->bindParam(':termo', $termo_like);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
