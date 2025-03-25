<?php
require_once '../config/database.php';
require_once '../models/Aviso.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php_errors.log');

header('Content-Type: application/json');

class AvisoController {
    private $model;

    public function __construct($pdo) {
        $this->model = new AvisoModel($pdo);
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
        $avisos = $this->model->listar();
        if ($avisos) {
            echo json_encode(["status" => "success", "data" => $avisos]);
        } else {
            echo json_encode(["status" => "error", "message" => "Nenhum aviso encontrado."]);
        }
    }

    private function editar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $aviso = $this->model->buscarPorId($data['id']);
        if ($aviso) {
            echo json_encode(["status" => "success", "data" => $aviso]);
        } else {
            echo json_encode(["status" => "error", "message" => "Aviso não encontrado."]);
        }
    }

    private function atualizar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->editar($data['id'], $data['titulo'], $data['mensagem'], $data['tipo'], $data['usuario_id']);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Aviso atualizado com sucesso!" : "Erro ao atualizar aviso."]);
    }

    private function cadastrar() {
        session_start(); // Inicia a sessão se não estiver iniciada
        
        // Verifica se o usuário está logado
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(["status" => "error", "message" => "Usuário não autenticado."]);
            return;
        }
    
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Valida campos obrigatórios (exceto usuario_id que vem da sessão)
        $required = ['titulo', 'mensagem', 'tipo'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                echo json_encode(["status" => "error", "message" => "Campo '$field' é obrigatório."]);
                return;
            }
        }
        
        $result = $this->model->cadastrar(
            $data['titulo'], 
            $data['mensagem'], 
            $data['tipo'], 
            $_SESSION['usuario_id'] // Pega da sessão
        );
        
        echo json_encode([
            "status" => $result ? "success" : "error", 
            "message" => $result ? "Aviso cadastrado com sucesso!" : "Erro ao cadastrar aviso."
        ]);
    }

    private function deletar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->deletar($data['id']);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Aviso excluído com sucesso!" : "Erro ao excluir aviso."]);
    }
}

$avisoController = new AvisoController($pdo);
$avisoController->handleRequest();
?>