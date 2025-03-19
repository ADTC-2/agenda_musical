<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php'); // Redireciona se já estiver logado
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Registro</h2>
                <?php if (isset($_SESSION['erro'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['erro']; ?></div>
                    <?php unset($_SESSION['erro']); ?>
                <?php endif; ?>
                <form action="routes/web.php?action=register" method="POST">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
                <p class="mt-3">Já tem uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </div>
    </div>
</body>
</html>