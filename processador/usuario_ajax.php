<?php
require_once '../config/config.php';
require_once '../models/Usuario.php';
require_once '../models/BaseModel.php';

$usuario = new Usuario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica as credenciais
    $usuario = $usuario->getByEmail($email);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Credenciais inválidas']);
    }
}