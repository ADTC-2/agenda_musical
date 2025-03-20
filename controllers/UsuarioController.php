<?php
require_once '../models/Usuario.php';

header('Content-Type: application/json'); // Garantir que o tipo de conteúdo seja JSON
error_reporting(0);
ini_set('display_errors', 0);

$usuarioModel = new UsuarioModel();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'listar':
            $usuarios = $usuarioModel->listar();
            if (is_array($usuarios)) {
                echo json_encode($usuarios); // Resposta correta em JSON
            } else {
                echo json_encode(["status" => "error", "message" => "Erro ao listar usuários."]);
            }
            exit();

        case 'editar':
            $id = $_POST['id'] ?? null;
            if ($id) {
                echo json_encode($usuarioModel->editar($id));
            } else {
                echo json_encode(["status" => "error", "message" => "ID não fornecido."]);
            }
            exit();

        case 'update':
            $id = $_POST['id'] ?? null;
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $tipo = $_POST['tipo'] ?? '';
            if ($id && $usuarioModel->atualizar($id, $nome, $email, $tipo)) {
                echo json_encode(["status" => "success", "message" => "Usuário atualizado com sucesso!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Erro ao atualizar usuário."]);
            }
            exit();

        case 'delete':
            $id = $_POST['id'] ?? null;
            if ($id && $usuarioModel->deletar($id)) {
                echo json_encode(["status" => "success", "message" => "Usuário excluído com sucesso!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Erro ao excluir usuário."]);
            }
            exit();

        case 'cadastrar':
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $tipo = $_POST['tipo'] ?? '';
            echo json_encode($usuarioModel->cadastrar($nome, $email, $senha, $tipo));
            exit();

        default:
            echo json_encode(["status" => "error", "message" => "Ação inválida!"]);
            exit();
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Erro: " . $e->getMessage()]);
    exit();
}   
?> 