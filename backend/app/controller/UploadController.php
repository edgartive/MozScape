<?php

require_once __DIR__ . '/../model/DAO/UploadDAO.php';
require_once __DIR__ . '/../model/DAO/PedidoUploadDAO.php';
require_once __DIR__ . '/../model/Upload.php';

class UploadController
{
    private $uploadDAO;
    private $db;
    private $pedidoUploadDAO;

    public function __construct($db)
    {
        $this->uploadDAO = new UploadDAO($db);
        $this->pedidoUploadDAO = new PedidoUploadDAO($db);
        $this->uploadDAO = new UploadDAO($db);
        $this->uploadDAO = new UploadDAO($db);
    }
    public function buscarUploadPorId($id_upload)
    {
        $query = "SELECT * FROM uploads WHERE id = :id_upload";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_upload', $id_upload, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
    public function curtirUpload($id_upload)
    {
        return $this->uploadDAO->curtirUpload($id_upload);
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
    public function criarUploadAprovado($id_pedido)
    {
        $baseDir = realpath(__DIR__ . '/../../') . '/';
        $backupDir = $baseDir . 'uploads/backups/';
        $aprovadosDir = $baseDir . 'uploads/aprovados/';

        // Garante diretório de aprovados
        if (!file_exists($aprovadosDir)) {
            mkdir($aprovadosDir, 0755, true);
        }

        $pedido = $this->pedidoUploadDAO->buscarPedidoPorId($id_pedido);
        if (!$pedido) {
            throw new Exception("Pedido não encontrado");
        }

        $nomeArquivo = $pedido['foto_url'];
        $caminhoBackup = $backupDir . $nomeArquivo;
        $caminhoAprovado = $aprovadosDir . $nomeArquivo;

        // Verificação tripla de segurança
        if (!file_exists($caminhoBackup)) {
            throw new Exception("Arquivo backup não encontrado");
        }

        // Tenta até 3 vezes copiar o arquivo
        $tentativas = 0;
        $copiado = false;

        while ($tentativas < 3 && !$copiado) {
            $tentativas++;
            $copiado = copy($caminhoBackup, $caminhoAprovado);
            if (!$copiado) sleep(1); // Espera 1 segundo entre tentativas
        }

        if (!$copiado) {
            throw new Exception("Falha ao copiar após 3 tentativas");
        }

        // Registra no banco de dados
        $upload = new Upload();
        $upload->setUsuarioId($pedido['id_usuario']);
        $upload->setTipo($pedido['tipo']);
        $upload->setDataUpload(date('Y-m-d H:i:s'));
        $upload->setDescricao($pedido['descricao']);
        $upload->setLikes(0);
        $upload->setFotoUrl($nomeArquivo);

        if (!$this->uploadDAO->criarUpload($upload)) {
            unlink($caminhoAprovado); // Remove se falhar no banco
            throw new Exception("Falha ao registrar upload");
        }

        // Atualiza status do pedido
        if (!$this->pedidoUploadDAO->atualizarStatus($id_pedido, 'aprovado')) {
            throw new Exception("Falha ao atualizar status");
        }

        return true;
    }
}
