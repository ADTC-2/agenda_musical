<?php
require_once '../config/database.php';

class EventoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM eventos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM eventos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrar($nome, $data_hora) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO eventos (nome, data_hora) VALUES (:nome, :data_hora)");
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':data_hora', $data_hora, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log do erro (opcional)
            error_log("Erro ao cadastrar evento: " . $e->getMessage());
            return false;
        }
    }

    public function editar($id, $nome, $data_hora) {
        $stmt = $this->pdo->prepare("UPDATE eventos SET nome = :nome, data_hora = :data_hora WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':data_hora', $data_hora, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function deletar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM eventos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>