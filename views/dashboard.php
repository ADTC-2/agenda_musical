<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário é admin
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$anoSelecionado = date('Y'); // Garante que $anoSelecionado tenha um valor padrão
$eventos = array_fill(0, 6, ['mes' => 'Março', 'status' => 'Finalizado']); // Simulação de eventos
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <!-- CSS -->
<!-- Carregar o Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Preload da fonte (apenas se necessário) -->
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin="anonymous">

<!-- Carregar o Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<!-- Carregar o favicon -->
<link rel="icon" href="../../assets/img/logoadtc2__new.png" type="image/x-icon">

<!-- Carregar o CSS personalizado -->
<link rel="stylesheet" href="../assets/css/dashboard.css">

<style>
    .welcome-title {
    font-size: 2em;
    font-weight: bold;
    text-align: center;
    color: #333;
    margin-bottom: 10px;
}

.welcome-message {
    font-size: 1.2em;
    text-align: center;
    color: #555;
    font-style: italic;
}

</style>
</head>

<body>
<header class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
            <div class="container-fluid">
                <button class="navbar-toggler me-auto" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Abrir menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="dashboard.php">Agenda Musical</a>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="menuLateral">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="menuLateral">Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav flex-grow-1">
                            <li class="nav-item">
                                <a class="nav-link active" href="dashboard.php">
                                    <i class="fas fa-home"></i> Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="../views/cultos/index.php">
                                    <i class="fas fa-home"></i> Cultos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="../views/eventos/index.php">
                                    <i class="fas fa-home"></i> Eventos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../controllers/AuthController.php?action=logout">
                                    <i class="fas fa-sign-out-alt"></i> Sair
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mt-5">
        <!-- Logo -->
        <div class="text-center">
            <img src="../assets/img/logo_vermelho.png" alt="Louvor - Agenda Musical" class="img-fluid" id="logo_vermelho">
        </div>

        <!-- Seção de eventos -->
        <section class="events">
            <h1 class="welcome-title">✨ Bem-vindo! 🎶</h1>
            <p class="welcome-message">É um prazer tê-lo(a) aqui. Explore nossos eventos e deixe a música guiar sua jornada!</p>
        </section>


    </main>

    <!-- Menu inferior fixo para Admin -->
    <footer class="menu-bottom">
        <nav>
            <a href="./eventos/index.php" class="menu-item" aria-label="Escalas">
                <i class="fas fa-home"></i> Eventos
            </a>
            <a href="./avisos/index.php" class="menu-item" aria-label="Avisos">
                <i class="fas fa-bell"></i> Avisos
            </a>
            <a href="./usuarios/index.php" class="menu-item" aria-label="Usuários">
                <i class="fas fa-users"></i> Usuários
            </a>
        </nav>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   
</body>
</html>