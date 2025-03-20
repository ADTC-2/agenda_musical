<?php
require_once '../config/database.php';
require_once '../models/Culto.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php_errors.log');

header('Content-Type: application/json');

class CultoController {
    private $model;

    public function __construct($pdo) {
        $this->model = new CultoModel($pdo);
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
        $cultos = $this->model->listar();
        if ($cultos) {
            echo json_encode(["status" => "success", "data" => $cultos]);
        } else {
            echo json_encode(["status" => "error", "message" => "Nenhum culto encontrado."]);
        }
    }

    private function editar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $culto = $this->model->buscarPorId($data['id']);
        if ($culto) {
            echo json_encode(["status" => "success", "data" => $culto]);
        } else {
            echo json_encode(["status" => "error", "message" => "Culto não encontrado."]);
        }
    }
    

    private function atualizar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->editar($data['id'], $data['titulo'], $data['dataHora'], $data['local']);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Culto atualizado com sucesso!" : "Erro ao atualizar culto."]);
    }

    private function cadastrar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->cadastrar($data['titulo'], $data['dataHora'], $data['local']);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Culto cadastrado com sucesso!" : "Erro ao cadastrar culto."]);
    }

    private function deletar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->deletar($data['id']);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Culto excluído com sucesso!" : "Erro ao excluir culto."]);
    }
}

$cultoController = new CultoController($pdo);
$cultoController->handleRequest();
?>
