<?php
require_once '../config/database.php';

// Listar usuários
if ($_GET['action'] === 'listar') {
    try {
        $stmt = $pdo->prepare('SELECT * FROM usuarios');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (Exception $e) {
        // Caso ocorra um erro no banco de dados, retorne um erro
        echo json_encode(["status" => "error", "message" => "Erro ao buscar dados: " . $e->getMessage()]);
    }
}

// Editar usuário
if ($_GET['action'] === 'editar') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

// Atualizar usuário
if ($_POST['action'] === 'update') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];

    $stmt = $pdo->prepare('UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->execute();
}

// Deletar usuário
if ($_GET['action'] === 'delete') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Cadastrar usuário
if ($_POST['action'] === 'cadastrar') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];

    // Verificando se o email já existe
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE email = :email');
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $emailExistente = $stmt->fetchColumn();

    if ($emailExistente > 0) {
        echo json_encode(["status" => "error", "message" => "O email já está em uso!"]);
        exit();
    }

    // Inserir novo usuário
    $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)');
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':tipo', $tipo);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Usuário cadastrado com sucesso!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao cadastrar usuário."]);
    }
}
?>


