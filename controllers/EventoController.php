<?php
require_once '../config/database.php';
require_once '../models/EventoModel.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php_errors.log');

header('Content-Type: application/json');

class EventoController {
    private $model;

    public function __construct($pdo) {
        $this->model = new EventoModel($pdo);
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
        $eventos = $this->model->listar();
        if ($eventos) {
            echo json_encode(["status" => "success", "data" => $eventos]);
        } else {
            echo json_encode(["status" => "error", "message" => "Nenhum evento encontrado."]);
        }
    }

    private function editar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $evento = $this->model->buscarPorId($data['id']);
        if ($evento) {
            echo json_encode(["status" => "success", "data" => $evento]);
        } else {
            echo json_encode(["status" => "error", "message" => "Evento não encontrado."]);
        }
    }

    private function atualizar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->editar($data['id'], $data['nome'], $data['data_hora']);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Evento atualizado com sucesso!" : "Erro ao atualizar evento."]);
    }

    private function cadastrar() {
        $data = json_decode(file_get_contents('php://input'), true);
    
        // Verifica se as chaves necessárias estão presentes no array
        if (!isset($data['nome']) || !isset($data['data_hora'])) {
            echo json_encode(["status" => "error", "message" => "Dados incompletos. 'nome' e 'data_hora' são obrigatórios."]);
            return;
        }
    
        $nome = $data['nome'];
        $data_hora = $data['data_hora'];
    
        $result = $this->model->cadastrar($nome, $data_hora);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Evento cadastrado com sucesso!" : "Erro ao cadastrar evento."]);
    }

    private function deletar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->deletar($data['id']);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Evento excluído com sucesso!" : "Erro ao excluir evento."]);
    }
}

$eventoController = new EventoController($pdo);
$eventoController->handleRequest();
?>