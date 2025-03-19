<?php
// routes/web.php

require_once '../controllers/AuthController.php';
$authController = new AuthController();

// Rota para exibir o formulário de login
if ($_SERVER['REQUEST_URI'] === '/login') {
    $authController->loginView();
}

// Rota para realizar o login
if ($_SERVER['REQUEST_URI'] === '/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $authController->login($email, $senha);
}

// Rota para realizar o logout
if ($_SERVER['REQUEST_URI'] === '/logout') {
    $authController->logout();
}
?>