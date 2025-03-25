<?php
require_once '../config/database.php';

class AvisoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar todos os avisos
    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM avisos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar um aviso por ID
    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM avisos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cadastrar um novo aviso
    public function cadastrar($titulo, $mensagem, $tipo, $usuario_id) {
        $stmt = $this->pdo->prepare("INSERT INTO avisos (titulo, mensagem, tipo, usuario_id) VALUES (:titulo, :mensagem, :tipo, :usuario_id)");
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':mensagem', $mensagem, PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Editar um aviso existente
    public function editar($id, $titulo, $mensagem, $tipo, $usuario_id) {
        $stmt = $this->pdo->prepare("UPDATE avisos SET titulo = :titulo, mensagem = :mensagem, tipo = :tipo, usuario_id = :usuario_id WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':mensagem', $mensagem, PDO::PARAM_STR);
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Deletar um aviso
    public function deletar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM avisos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>