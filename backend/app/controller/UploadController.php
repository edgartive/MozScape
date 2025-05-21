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

    public function listarUploads()
    {
        return $this->uploadDAO->listarUploads();
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
        $query = "SELECT u.* FROM UPLOAD u
              JOIN usuarios us ON u.usuario_id = us.id_usuario
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
        $query = "SELECT * FROM UPLOAD WHERE tipo = :categoria ORDER BY data_upload DESC";
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
}
