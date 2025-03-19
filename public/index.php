<?php
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION["usuario_id"])) {
    // Se o usuário estiver logado, redireciona para o painel
    header("Location: ../views/dashboard.php");
    exit;
} else {
    // Caso contrário, leva para a tela de login
    header("Location: ../views/login.php");
    exit;
}

// Se estiver logado, carrega a aplicação
require_once '../config/config.php';
require_once '../routes/web.php';
?>