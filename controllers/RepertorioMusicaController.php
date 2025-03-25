<?php
require_once '../config/database.php';
require_once '../models/RepertorioMusicaModel.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php_errors.log');

header('Content-Type: application/json');

class RepertorioMusicaController {
    private $model;
    private $repertorioModel; // Add this property
    private $musicaModel; // Add this property
    public function __construct($pdo) {
        $this->model = new RepertorioMusicaModel($pdo);
        $this->repertorioModel = new RepertorioMusicaModel($pdo); // Initialize the property
        $this->musicaModel = new RepertorioMusicaModel($pdo); // Initialize the property
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
                case 'listarRepertorios': // Nova ação para listar repertórios
                    $this->listarRepertorios();
                    break;
                case 'listarMusicas':    // Nova ação para listar músicas
                    $this->listarMusicas();
                    break;
                default:
                    http_response_code(400); // Bad Request
                    echo json_encode(["status" => "error", "message" => "Ação inválida!"]);
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["status" => "error", "message" => "Erro: " . $e->getMessage()]);
        }
    }
    

    private function listar() {
        try {
            $repertoriosMusicas = $this->model->listar();
            
            // Verifica se dados foram retornados
            if ($repertoriosMusicas && count($repertoriosMusicas) > 0) {
                echo json_encode(["status" => "success", "data" => $repertoriosMusicas]);
            } else {
                echo json_encode(["status" => "error", "message" => "Nenhum registro encontrado."]);
            }
        } catch (Exception $e) {
            // Caso ocorra um erro durante a execução
            echo json_encode(["status" => "error", "message" => "Erro ao buscar dados: " . $e->getMessage()]);
        }
    }
    

    private function editar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $repertorioMusica = $this->model->buscarPorId($data['repertorio_id'], $data['musica_id']);
        if ($repertorioMusica) {
            echo json_encode(["status" => "success", "data" => $repertorioMusica]);
        } else {
            echo json_encode(["status" => "error", "message" => "Registro não encontrado."]);
        }
    }

    private function atualizar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->editar($data['repertorio_id'], $data['musica_id'], $data['categoria']);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Registro atualizado com sucesso!" : "Erro ao atualizar registro."]);
    }

    private function cadastrar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->cadastrar($data['repertorio_id'], $data['musica_id'], $data['categoria']);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Registro cadastrado com sucesso!" : "Erro ao cadastrar registro."]);
    }

    private function deletar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->deletar($data['repertorio_id'], $data['musica_id']);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Registro excluído com sucesso!" : "Erro ao excluir registro."]);
    }
    // Nova função para listar repertórios
    private function listarRepertorios() {
        $repertorios = $this->repertorioModel->listar();
        if ($repertorios) {
            echo json_encode(["status" => "success", "data" => $repertorios]);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(["status" => "error", "message" => "Nenhum repertório encontrado."]);
        }
    }

    // Nova função para listar músicas
    private function listarMusicas() {
        $musicas = $this->musicaModel->listar();
        if ($musicas) {
            echo json_encode(["status" => "success", "data" => $musicas]);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(["status" => "error", "message" => "Nenhuma música encontrada."]);
        }
    }
}



$repertorioMusicaController = new RepertorioMusicaController($pdo);
$repertorioMusicaController->handleRequest();
?>