<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    die("Acesso negado. Faça login para continuar.");
}

// Exibe o perfil do usuário
echo "Bem-vindo, usuário!";
