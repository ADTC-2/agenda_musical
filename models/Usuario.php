<?php
require_once '../config/database.php';

class UsuarioModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function listar() {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function editar($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $nome, $email, $tipo) {
        $stmt = $this->pdo->prepare('UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':tipo', $tipo);
        return $stmt->execute();
    }

    public function deletar($id) {
        $stmt = $this->pdo->prepare('DELETE FROM usuarios WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function cadastrar($nome, $email, $senha, $tipo) {
        // Verifica se o e-mail já existe
        $stmt = $this->pdo->prepare('SELECT id FROM usuarios WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetch()) {
            return ["status" => "error", "message" => "O email já está em uso!"];
        }

        // Inserção do novo usuário
        $stmt = $this->pdo->prepare('INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)');
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', password_hash($senha, PASSWORD_DEFAULT));
        $stmt->bindParam(':tipo', $tipo);

        return $stmt->execute() ? ["status" => "success", "message" => "Usuário cadastrado com sucesso!"] : ["status" => "error", "message" => "Erro ao cadastrar usuário."];
    }

    public function autenticar($email, $senha) {
        $stmt = $this->pdo->prepare("SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($usuario && password_verify($senha, $usuario['senha'])) ? $usuario : false;
    }
}
?>