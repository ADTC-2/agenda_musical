<?php
require_once '../config/database.php';
require_once '../models/Repertorio.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php_errors.log');

header('Content-Type: application/json');

class RepertorioController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new RepertorioModel($pdo);
    }

    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = isset($_POST['action']) ? $_POST['action'] : '';
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
                case 'listarEventos':
                    $this->listarEventos();
                    break;
                case 'listarMusicasRepertorio':
                    $this->listarMusicasRepertorio();
                    break;
                default:
                    echo json_encode(["status" => "error", "message" => "Ação inválida!"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Erro: " . $e->getMessage()]);
        }
    }

    private function listar() {
        $repertorios = $this->model->listar();
        if (empty($repertorios)) {
            echo json_encode(["status" => "error", "message" => "Nenhum repertório encontrado."]);
        } else {
            echo json_encode(["status" => "success", "data" => $repertorios]);
        }
    }

    private function editar() {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        if (!$id) {
            echo json_encode(["status" => "error", "message" => "ID do repertório não fornecido."]);
            return;
        }
        $repertorio = $this->model->buscarPorId($id);
        echo json_encode(["status" => $repertorio ? "success" : "error", "data" => $repertorio ?: "Repertório não encontrado."]);
    }

    private function atualizar() {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $evento_id = isset($_POST['evento_id']) ? $_POST['evento_id'] : null;

        if (!$id || !$evento_id) {
            echo json_encode(["status" => "error", "message" => "Dados incompletos para atualização."]);
            return;
        }

        $result = $this->model->atualizar($id, $evento_id);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Repertório atualizado com sucesso!" : "Erro ao atualizar repertório."]);
    }

    private function cadastrar() {
        try {
            $evento_id = isset($_POST['evento_id']) ? $_POST['evento_id'] : null;
    
            if (!$evento_id) {
                throw new Exception("Dados incompletos para cadastro.");
            }
    
            // Verifica se o evento existe
            $stmt = $this->pdo->prepare('SELECT id FROM eventos WHERE id = :evento_id');
            $stmt->bindParam(':evento_id', $evento_id, PDO::PARAM_INT);
            $stmt->execute();
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                throw new Exception("Evento não encontrado.");
            }
    
            $result = $this->model->cadastrar($evento_id);
            if (!$result) {
                throw new Exception("Erro ao cadastrar repertório.");
            }
    
            echo json_encode(["status" => "success", "message" => "Repertório cadastrado com sucesso!"]);
        } catch (Exception $e) {
            error_log("Erro ao cadastrar repertório: " . $e->getMessage());
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    private function deletar() {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        if (!$id) {
            echo json_encode(["status" => "error", "message" => "ID do repertório não fornecido."]);
            return;
        }
        $result = $this->model->deletar($id);
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Repertório excluído com sucesso!" : "Erro ao excluir repertório."]);
    }

    public function listarEventos() {
        header('Content-Type: application/json');
    
        try {
            $query = "SELECT id, nome, data_hora FROM eventos";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            echo json_encode([
                'status' => 'success',
                'data' => $eventos
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Erro ao listar eventos: ' . $e->getMessage()
            ]);
        }
    }

    private function listarMusicasRepertorio() {
        try {
            $repertorio_id = isset($_POST['repertorio_id']) ? $_POST['repertorio_id'] : null;
            
            if (!$repertorio_id) {
                throw new Exception("ID do repertório não fornecido.");
            }

            $query = "SELECT m.id, m.titulo, m.tom, m.tipo, m.cantor_banda, rm.categoria 
                      FROM musicas m
                      JOIN repertorio_musica rm ON m.id = rm.musica_id
                      WHERE rm.repertorio_id = :repertorio_id
                      ORDER BY m.titulo";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':repertorio_id', $repertorio_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $musicas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($musicas)) {
                echo json_encode([
                    "status" => "success", 
                    "data" => [],
                    "message" => "Nenhuma música encontrada para este repertório."
                ]);
            } else {
                echo json_encode([
                    "status" => "success", 
                    "data" => $musicas
                ]);
            }
        } catch (Exception $e) {
            error_log("Erro ao listar músicas do repertório: " . $e->getMessage());
            echo json_encode([
                "status" => "error", 
                "message" => "Erro ao listar músicas do repertório: " . $e->getMessage()
            ]);
        }
    }
}

$repertorioController = new RepertorioController($pdo);
$repertorioController->handleRequest();
?>