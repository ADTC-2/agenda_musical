<?php
require_once '../config/database.php';

class RepertorioModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar todos os repertórios com informações do evento
    public function listar() {
        try {
            $query = "SELECT r.id, r.evento_id, e.nome AS evento_nome, e.data_hora AS evento_data
                      FROM repertorios r
                      INNER JOIN eventos e ON r.evento_id = e.id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $repertorios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Para cada repertório, buscar as músicas associadas
            foreach ($repertorios as &$repertorio) {
                $queryMusicas = "SELECT m.id, m.titulo, rm.categoria 
                                 FROM musicas m
                                 JOIN repertorio_musica rm ON m.id = rm.musica_id
                                 WHERE rm.repertorio_id = :repertorio_id";
                $stmtMusicas = $this->pdo->prepare($queryMusicas);
                $stmtMusicas->bindParam(':repertorio_id', $repertorio['id'], PDO::PARAM_INT);
                $stmtMusicas->execute();
                $repertorio['musicas'] = $stmtMusicas->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $repertorios;
        } catch (PDOException $e) {
            error_log("Erro ao listar repertórios: " . $e->getMessage());
            return [];
        }
    }

    // Buscar um repertório por ID
    public function buscarPorId($id) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM repertorios WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar repertório por ID: " . $e->getMessage());
            return null;
        }
    }

    // Cadastrar um novo repertório
    public function cadastrar($evento_id) {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO repertorios (evento_id) VALUES (:evento_id)');
            $stmt->bindParam(':evento_id', $evento_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao cadastrar repertório: " . $e->getMessage());
            return false;
        }
    }

    // Atualizar um repertório existente
    public function atualizar($id, $evento_id) {
        try {
            $stmt = $this->pdo->prepare('UPDATE repertorios SET evento_id = :evento_id WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':evento_id', $evento_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar repertório: " . $e->getMessage());
            return false;
        }
    }

    // Deletar um repertório
    public function deletar($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM repertorios WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao deletar repertório: " . $e->getMessage());
            return false;
        }
    }
}
?>