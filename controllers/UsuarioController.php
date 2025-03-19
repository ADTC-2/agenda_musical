<?php
// controllers/UsuarioController.php

require_once '../models/Usuario.php';

class UsuarioController {
    private $model;

    public function __construct() {
        $this->model = new UsuarioModel();
    }

    public function listar() {
        echo json_encode($this->model->listar());
    }

    public function editar($id) {
        echo json_encode($this->model->editar($id));
    }

    public function atualizar($id, $nome, $email, $tipo) {
        if ($this->model->atualizar($id, $nome, $email, $tipo)) {
            echo json_encode(["status" => "success", "message" => "Usuário atualizado com sucesso!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erro ao atualizar usuário."]);
        }
    }

    public function deletar($id) {
        if ($this->model->deletar($id)) {
            echo json_encode(["status" => "success", "message" => "Usuário deletado com sucesso!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erro ao deletar usuário."]);
        }
    }

    public function cadastrar($nome, $email, $senha, $tipo) {
        echo json_encode($this->model->cadastrar($nome, $email, $senha, $tipo));
    }
}

// Instanciando o controlador e executando a ação
$controller = new UsuarioController();

if ($_GET['action'] === 'listar') {
    $controller->listar();
} elseif ($_GET['action'] === 'editar') {
    $controller->editar($_GET['id']);
} elseif ($_POST['action'] === 'update') {
    $controller->atualizar($_POST['id'], $_POST['nome'], $_POST['email'], $_POST['tipo']);
} elseif ($_GET['action'] === 'delete') {
    $controller->deletar($_GET['id']);
} elseif ($_POST['action'] === 'cadastrar') {
    $controller->cadastrar($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['tipo']);
}
?>


