<?php
require_once '../config/database.php';
require_once '../models/Musica.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '../../logs/php_errors.log');

header('Content-Type: application/json');

class MusicaController
{
    private $musicaModel;

    public function __construct($pdo)
    {
        $this->musicaModel = new Musica($pdo);
    }

    public function handleRequest()
    {
        $action = $_POST['action'] ?? '';

        try {
            switch ($action) {
                case 'listar':
                    $this->listar();
                    break;
                case 'cadastrar':
                    $this->cadastrar();
                    break;
                case 'buscar':
                    $this->buscar();
                    break;
                case 'editar':
                    $this->editar();
                    break;
                case 'excluir':
                    $this->excluir();
                    break;
                default:
                    echo json_encode(["status" => "error", "message" => "Ação inválida!"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Erro: " . $e->getMessage()]);
        }
    }

    private function listar() {
        $musicas = $this->musicaModel->listar();
        if ($musicas) {
            echo json_encode(["status" => "success", "data" => $musicas]);
        } else {
            echo json_encode(["status" => "error", "message" => "Nenhuma música encontrada."]);
        }
    }

    private function cadastrar()
    {
        if (empty($_POST['titulo']) || empty($_POST['tipo'])) {
            echo json_encode(["status" => "error", "message" => "O título e o tipo da música são obrigatórios."]);
            return;
        }

        $result = $this->musicaModel->cadastrar(
            $_POST['titulo'], 
            $_POST['cantor_banda'] ?? '', 
            $_POST['tipo'], 
            $_POST['tom'] ?? '', 
            $_POST['bpm'] ?? 0, 
            $_POST['link'] ?? '', 
            $_POST['arquivo'] ?? ''
        );

        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Música cadastrada com sucesso!" : "Erro ao cadastrar música."]);
    }

    private function buscar()
    {
        if (empty($_POST['id'])) {
            echo json_encode(["status" => "error", "message" => "ID da música é obrigatório."]);
            return;
        }

        $musica = $this->musicaModel->buscarPorId($_POST['id']);
        if ($musica) {
            echo json_encode(["status" => "success", "data" => $musica]);
        } else {
            echo json_encode(["status" => "error", "message" => "Música não encontrada."]);
        }
    }

    private function editar()
    {
        if (empty($_POST['id']) || empty($_POST['titulo']) || empty($_POST['tipo'])) {
            echo json_encode(["status" => "error", "message" => "ID, título e tipo da música são obrigatórios."]);
            return;
        }

        $result = $this->musicaModel->editar(
            $_POST['id'],
            $_POST['titulo'],
            $_POST['cantor_banda'] ?? '',
            $_POST['tipo'],
            $_POST['tom'] ?? '',
            $_POST['bpm'] ?? 0,
            $_POST['link'] ?? '',
            $_POST['arquivo'] ?? ''
        );

        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Música atualizada com sucesso!" : "Erro ao atualizar música."]);
    }

    private function excluir()
    {
        if (empty($_POST['id'])) {
            echo json_encode(["status" => "error", "message" => "ID da música é obrigatório."]);
            return;
        }

        $result = $this->musicaModel->excluir($_POST['id']);

        $response = [
            "status" => $result ? "success" : "error", 
            "message" => $result ? "Música excluída com sucesso!" : "Erro ao excluir música."
        ];

        echo json_encode($response);
    }
}

// Assuming you have a PDO instance available

$musicaController = new MusicaController($pdo);
$musicaController->handleRequest();
?>