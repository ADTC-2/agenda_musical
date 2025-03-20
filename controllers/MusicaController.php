<?php
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

    private function listar()
    {
        $musicas = $this->musicaModel->listar();
        if ($musicas) {
            echo json_encode(["status" => "success", "data" => $musicas]);
        } else {
            echo json_encode(["status" => "error", "message" => "Nenhuma música encontrada."]);
        }
    }

    private function cadastrar()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['titulo'])) {
            echo json_encode(["status" => "error", "message" => "O título da música é obrigatório."]);
            return;
        }

        $result = $this->musicaModel->cadastrar(
            $data['titulo'], 
            $data['cantorBanda'] ?? '', 
            $data['tom'] ?? '', 
            $data['bpm'] ?? 0, 
            $data['link'] ?? '', 
            $data['arquivo'] ?? ''
        );

        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Música cadastrada com sucesso!" : "Erro ao cadastrar música."]);
    }

    private function buscar()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['id'])) {
            echo json_encode(["status" => "error", "message" => "ID da música é obrigatório."]);
            return;
        }

        $musica = $this->musicaModel->buscarPorId($data['id']);
        if ($musica) {
            echo json_encode(["status" => "success", "data" => $musica]);
        } else {
            echo json_encode(["status" => "error", "message" => "Música não encontrada."]);
        }
    }

    private function editar()
    {
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (empty($data['id']) || empty($data['titulo'])) {
            echo json_encode(["status" => "error", "message" => "ID e título da música são obrigatórios."]);
            return;
        }
    
        $result = $this->musicaModel->editar(
            $data['id'],
            $data['titulo'],
            $data['cantorBanda'] ?? '',
            $data['tom'] ?? '',
            $data['bpm'] ?? 0,
            $data['link'] ?? '',
            $data['arquivo'] ?? ''
        );
    
        echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Música atualizada com sucesso!" : "Erro ao atualizar música."]);
    }

 // Método de exclusão corrigido
 private function excluir()
 {
     $data = json_decode(file_get_contents('php://input'), true); // Decodifica o JSON enviado via POST

     if (empty($data['id'])) {
         // Se não passar um ID, responde com erro
         echo json_encode(["status" => "error", "message" => "ID da música é obrigatório."]);
         return;
     }

     // Chama o método de exclusão do model
     $result = $this->musicaModel->excluir($data['id']); // Chama a exclusão correta no modelo de usuários

     // Responde com o status da operação
     $response = [
         "status" => $result ? "success" : "error", 
         "message" => $result ? "Música excluída com sucesso!" : "Erro ao excluir música."
     ];

     // Certifique-se de que o cabeçalho da resposta está correto
     header('Content-Type: application/json');  // Adiciona esse cabeçalho para indicar que é JSON
     echo json_encode($response);  // Retorna o JSON
 }

    
    
}

// Assuming you have a PDO instance available
$musicaController = new MusicaController($pdo);
$musicaController->handleRequest();
?>