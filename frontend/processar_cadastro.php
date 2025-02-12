<?php
require __DIR__ . '../backend/app/core/EasyPDO.php';

require 'Model/UsuarioModel.php';
require 'Controller/UsuarioController.php';
// Cria a instância do EasyPDO
$easyPDO = new EasyPDO();

// Cria a instância do UsuarioModel
$usuarioModel = new UsuarioModel($easyPDO);

// Cria a instância do UsuarioController
$usuarioController = new UsuarioController($usuarioModel);

// Processa o cadastro
$usuarioController->cadastrar();
