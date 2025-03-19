<?php
// models/UsuarioModel.php

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
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $nome, $email, $tipo) {
        $stmt = $this->pdo->prepare('UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':tipo', $tipo);
        return $stmt->execute();
    }

    public function deletar($id) {
        $stmt = $this->pdo->prepare('DELETE FROM usuarios WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
        // Função para autenticar o usuário
    public function autenticar($email, $senha) {
        $usuario = $this->getByEmail($email);
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario; // Retorna os dados do usuário autenticado
        }
        return false; // Retorna falso se as credenciais forem inválidas
    }
    
    // Função para obter um usuário pelo email
    private function getByEmail($email) {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function cadastrar($nome, $email, $senha, $tipo) {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $emailExistente = $stmt->fetchColumn();

        if ($emailExistente > 0) {
            return ["status" => "error", "message" => "O email já está em uso!"];
        }

        $stmt = $this->pdo->prepare('INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)');
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', password_hash($senha, PASSWORD_DEFAULT));
        $stmt->bindParam(':tipo', $tipo);
        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Usuário cadastrado com sucesso!"];
        } else {
            return ["status" => "error", "message" => "Erro ao cadastrar usuário."];
        }
    }
}
?>