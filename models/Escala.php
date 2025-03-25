<?php
require_once '../config/database.php';

class EscalaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        $stmt = $this->pdo->query("
            SELECT 
                escalas.id, 
                escalas.culto_id, 
                escalas.usuario_id, 
                escalas.instrumento, 
                cultos.titulo AS culto_titulo, 
                usuarios.nome AS usuario_nome 
            FROM escalas
            INNER JOIN cultos ON escalas.culto_id = cultos.id
            INNER JOIN usuarios ON escalas.usuario_id = usuarios.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM escalas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrar($culto_id, $usuario_id, $instrumento) {
        $stmt = $this->pdo->prepare("INSERT INTO escalas (culto_id, usuario_id, instrumento) VALUES (:culto_id, :usuario_id, :instrumento)");
        $stmt->bindParam(':culto_id', $culto_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':instrumento', $instrumento, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function editar($id, $culto_id, $usuario_id, $instrumento) {
        $stmt = $this->pdo->prepare("UPDATE escalas SET culto_id = :culto_id, usuario_id = :usuario_id, instrumento = :instrumento WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':culto_id', $culto_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':instrumento', $instrumento, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function deletar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM escalas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function listarUsuarios() {
        $stmt = $this->pdo->query("SELECT id, nome FROM usuarios ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function listarCultos() {
        $stmt = $this->pdo->query("SELECT id, nome FROM cultos ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>