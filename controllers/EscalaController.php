<?php
require_once '../config/database.php';
require_once '../models/Escala.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php_errors.log');

header('Content-Type: application/json');

class EscalaController {
    private $model;

    public function __construct($pdo) {
        $this->model = new EscalaModel($pdo);
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            if (!$data) {
                echo json_encode(["status" => "error", "message" => "Erro ao processar JSON."]);
                exit;
            }
            $action = $data['action'] ?? '';
        }

        try {
            switch ($action) {
                case 'listar':
                    $this->listar();
                    break;
                case 'cadastrar':
                    $this->cadastrar();
                    break;
                case 'editar':
                    $this->editar();
                    break;
                case 'atualizar':
                    $this->atualizar();
                    break;
                case 'deletar':
                    $this->deletar();
                    break;
                default:
                    echo json_encode(["status" => "error", "message" => "Ação inválida!"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Erro: " . $e->getMessage()]);
        }
    }

    private function listar() {
        $escalas = $this->model->listar();
        if ($escalas) {
            echo json_encode(["status" => "success", "data" => $escalas]);
        } else {
            echo json_encode(["status" => "error", "message" => "Nenhuma escala encontrada."]);
        }
    }

    private function editar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $escala = $this->model->buscarPorId($data['id']);
        if ($escala) {
            echo json_encode(["status" => "success", "data" => $escala]);
        } else {
            echo json_encode(["status" => "error", "message" => "Escala não encontrada."]);
        }
    }

    private function atualizar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->editar(
            $data['id'],
            $data['culto_id'],
            $data['usuario_id'],
            $data['instrumento']
        );
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Escala atualizada com sucesso!" : "Erro ao atualizar escala."]);
    }

    private function cadastrar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->cadastrar(
            $data['culto_id'],
            $data['usuario_id'],
            $data['instrumento']
        );
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Escala cadastrada com sucesso!" : "Erro ao cadastrar escala."]);
    }

    private function deletar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->deletar($data['id']);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Escala excluída com sucesso!" : "Erro ao excluir escala."]);
    }
}

$escalaController = new EscalaController($pdo);
$escalaController->handleRequest();
?>