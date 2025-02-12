<?php
class UsuarioModel
{
    private $easyPDO;

    public function __construct($easyPDO)
    {
        $this->easyPDO = $easyPDO;
    }

    public function cadastrarUsuario($dadosUsuario)
    {
        $query = "INSERT INTO usuarios (
            nome_completo, email, senha, username, foto_de_perfil_url, 
            frase_favorita, uploader, biografia, links
        ) VALUES (
            :nome_completo, :email, :senha, :username, :foto_de_perfil_url, 
            :frase_favorita, :uploader, :biografia, :links
        )";
        $parameters = [
            ':nome_completo' => $dadosUsuario['nome_completo'],
            ':email' => $dadosUsuario['email'],
            ':senha' => password_hash($dadosUsuario['senha'], PASSWORD_BCRYPT),
            ':username' => $dadosUsuario['username'],
            ':foto_de_perfil_url' => $dadosUsuario['foto_de_perfil_url'],
            ':frase_favorita' => $dadosUsuario['frase_favorita'],
            ':uploader' => $dadosUsuario['uploader'],
            ':biografia' => $dadosUsuario['biografia'],
            ':links' => $dadosUsuario['links']
        ];
        $this->easyPDO->insert($query, $parameters);
    }

    public function listarUsuarios()
    {
        $query = "SELECT * FROM usuarios";
        return $this->easyPDO->select($query);
    }
}
