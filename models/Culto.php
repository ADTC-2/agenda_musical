<?php
require_once '../config/database.php';

class CultoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM cultos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cultos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrar($titulo, $data_hora, $local) {
        $stmt = $this->pdo->prepare("INSERT INTO cultos (titulo, data_hora, local) VALUES (:titulo, :data_hora, :local)");
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':data_hora', $data_hora, PDO::PARAM_STR);
        $stmt->bindParam(':local', $local, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function editar($id, $titulo, $data_hora, $local) {
        $stmt = $this->pdo->prepare("UPDATE cultos SET titulo = :titulo, data_hora = :data_hora, local = :local WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':data_hora', $data_hora, PDO::PARAM_STR);
        $stmt->bindParam(':local', $local, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function deletar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM cultos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>