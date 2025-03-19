<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir o controlador AuthController
require_once '../controllers/AuthController.php';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar o e-mail e a senha do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Instanciar o controlador de autenticação
    $authController = new AuthController();

    // Tentar fazer o login
    $resultado = $authController->login($email, $senha);

    // Verificar o resultado do login
    if ($resultado['status'] == 'success') {
        // Se o login for bem-sucedido, redirecionar para a página inicial
        header('Location: ../views/dashboard.php');
        exit; // Garantir que o script pare aqui
    } else {
        // Se houver erro, exibir a mensagem de erro
        $erro = $resultado['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Agenda Musical</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="shortcut icon" href="../assets/img/logo.png" type="image/x-icon">
    <script>
        if (performance.navigation.type === 2) {
            // Se a página foi carregada por meio do botão "Voltar", recarrega a página
            location.reload(true);
        }
    </script>
</head>
<body>
    <div class="login-container">
        <img src="../assets/img/logo.png" alt="Logo" class="logo">
        
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="text" name="email" class="form-control" placeholder="E-mail" required>
            </div>
            <div class="mb-3">
                <input type="password" name="senha" class="form-control" placeholder="Senha" required>
            </div>
            <button type="submit" class="btn btn-laranja">Entrar</button>
        </form>
        <a href="#">Esqueci a senha</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>