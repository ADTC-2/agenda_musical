<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avisos</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="../../assets/img/logoadtc2__new.png" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <style>
        .aviso-card {
            border: 1px solid #ddd;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            word-wrap: break-word;  /* Quebra palavras longas */
            overflow-wrap: break-word;  /* Alternativa moderna */
            white-space: pre-line; 
        }

        .aviso-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .aviso-card h5 {
            margin: 0 0 10px 0;
            font-size: 1.5em;
            color: #333;
        }

        .aviso-card p {
            margin: 5px 0;
            color: #555;
        }

        .aviso-card .btn {
            margin-top: 10px;
        }

        #searchInput {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
            max-width: 400px;
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
                                <a class="nav-link active" href="../cultos/index.php">
                                    <i class="fas fa-home"></i> Cultos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="../eventos/index.php">
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

    <main class="container mt-5 pt-5">
        <!-- Logo -->
        <div class="text-center mb-4">
            <img src="../../assets/img/logo_vermelho.png" alt="Louvor - Agenda Musical" class="img-fluid" id="logo_vermelho">
        </div>

        <!-- Botão para abrir o modal de cadastro -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Cadastrar Aviso
        </button>

        <!-- Campo de busca -->
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar aviso...">
        </div>

        <!-- Cards de avisos -->
        <div id="avisosList" class="row">
            <!-- Cards serão carregados aqui via AJAX -->
        </div>
    </main>

    <!-- Modal para cadastrar aviso -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Cadastrar Aviso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createAvisoForm">
                        <div class="mb-3">
                            <label for="createTitulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="createTitulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="createMensagem" class="form-label">Mensagem</label>
                            <textarea class="form-control" id="createMensagem" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="createTipo" class="form-label">Tipo</label>
                            <select class="form-control" id="createTipo" required>
                                <option value="geral">Geral</option>
                                <option value="musico">Músico</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar aviso -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Aviso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAvisoForm">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editTitulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="editTitulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMensagem" class="form-label">Mensagem</label>
                            <textarea class="form-control" id="editMensagem" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editTipo" class="form-label">Tipo</label>
                            <select class="form-control" id="editTipo" required>
                                <option value="geral">Geral</option>
                                <option value="musico">Músico</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <!-- Menu inferior fixo para Admin -->
    <footer class="menu-bottom">
        <nav>
            <a href="../eventos/index.php" class="menu-item" aria-label="Escalas">
                <i class="fas fa-home"></i> Eventos
            </a>
            <a href="../avisos/index.php" class="menu-item" aria-label="Avisos">
                <i class="fas fa-bell"></i> Avisos
            </a>
            <a href="../usuarios/index.php" class="menu-item" aria-label="Usuários">
                <i class="fas fa-users"></i> Usuários
            </a>
        </nav>
    </footer>  
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            function carregarAvisos() {
    $.ajax({
        url: '../../controllers/AvisoController.php',
        method: 'GET',
        data: { action: 'listar' },
        dataType: 'json',
        success: function(response) {
            if (response.status === "error") {
                alert(response.message);
                return;
            }
            if (response.status === "success" && Array.isArray(response.data)) {
                let avisoCards = response.data.map(aviso => {
                    // Formata a data em português (ex: "25/03/2025" ou "25 de março de 2025")
                    const dataFormatada = new Date(aviso.criado_em).toLocaleDateString('pt-BR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                        // Para formato com nome do mês:
                        // day: '2-digit',
                        // month: 'long',
                        // year: 'numeric'
                    });

                    return `
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="aviso-card p-3 border rounded">
                                <h5>${aviso.titulo}</h5>
                                <p class="mb-1"><strong>Mensagem:</strong></p>
                                <p class="aviso-mensagem">${aviso.mensagem}</p>
                                <p class="mb-1"><strong>Tipo:</strong> ${aviso.tipo}</p>
                                <p class="mb-1"><strong>Criado em:</strong> ${dataFormatada}</p>
                                <div class="d-flex gap-2 mt-2">
                                    <button class="btn btn-info btn-sm flex-grow-1 edit-btn" data-id="${aviso.id}">Editar</button>
                                    <button class="btn btn-danger btn-sm flex-grow-1 delete-btn" data-id="${aviso.id}">Excluir</button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
                $('#avisosList').html(avisoCards);
            } else {
                console.error("Erro: resposta inesperada", response);
                alert("Erro ao carregar avisos.");
            }
        },
        error: function(xhr) {
            console.error("Erro AJAX:", xhr.responseText);
            alert('Erro na comunicação com o servidor.');
        }
    });
}

            $('#createAvisoForm').on('submit', function (e) {
                e.preventDefault();
                let avisoData = {
                    action: 'cadastrar',
                    titulo: $('#createTitulo').val(),
                    mensagem: $('#createMensagem').val(),
                    tipo: $('#createTipo').val()
                };

                $.ajax({
                    url: '../../controllers/AvisoController.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(avisoData),
                    dataType: 'json',
                    success: function(response) {
                        alert(response.message);
                        if (response.status === "success") {
                            $('#createModal').modal('hide');
                            $('#createAvisoForm')[0].reset();
                            carregarAvisos();
                        }
                    },
                    error: function(xhr) {
                        console.error("Erro ao cadastrar:", xhr.responseText);
                        alert('Erro ao cadastrar.');
                    }
                });
            });

            $(document).on('click', '.edit-btn', function () {
                let id = $(this).data('id');
                $.ajax({
                    url: '../../controllers/AvisoController.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ action: 'editar', id: id }),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success" && response.data) {
                            $('#editId').val(response.data.id);
                            $('#editTitulo').val(response.data.titulo);
                            $('#editMensagem').val(response.data.mensagem);
                            $('#editTipo').val(response.data.tipo);
                            $('#editModal').modal('show');
                        } else {
                            alert("Erro ao carregar os dados do aviso.");
                        }
                    },
                    error: function(xhr) {
                        console.error("Erro ao buscar aviso:", xhr.responseText);
                        alert('Erro ao buscar dados do aviso.');
                    }
                });
            });

            $('#editAvisoForm').on('submit', function (e) {
                e.preventDefault();
                let avisoData = {
                    action: 'atualizar',
                    id: $('#editId').val(),
                    titulo: $('#editTitulo').val(),
                    mensagem: $('#editMensagem').val(),
                    tipo: $('#editTipo').val()
                };

                $.ajax({
                    url: '../../controllers/AvisoController.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(avisoData),
                    dataType: 'json',
                    success: function(response) {
                        alert(response.message);
                        if (response.status === "success") {
                            $('#editModal').modal('hide');
                            carregarAvisos();
                        }
                    },
                    error: function(xhr) {
                        console.error("Erro ao atualizar:", xhr.responseText);
                        alert('Erro ao atualizar o aviso.');
                    }
                });
            });

            $(document).on('click', '.delete-btn', function () {
                let id = $(this).data('id');
                if (!confirm("Tem certeza que deseja excluir este aviso?")) return;

                $.ajax({
                    url: '../../controllers/AvisoController.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ action: 'deletar', id: id }),
                    dataType: 'json',
                    success: function(response) {
                        alert(response.message);
                        if (response.status === "success") {
                            carregarAvisos();
                        }
                    },
                    error: function(xhr) {
                        console.error("Erro ao excluir:", xhr.responseText);
                        alert('Erro ao excluir o aviso.');
                    }
                });
            });

            carregarAvisos();
        });
    </script>
</body>

</html>
 