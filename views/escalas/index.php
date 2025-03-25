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
    <title>Escalas</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="../../assets/img/logoadtc2__new.png" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        .escala-card {
            border: 1px solid #ddd;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .escala-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .escala-card h5 {
            margin: 0 0 10px 0;
            font-size: 1.5em;
            color: #333;
        }

        .escala-card p {
            margin: 5px 0;
            color: #555;
        }

        .escala-card .btn {
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .table-responsive {
                display: none;
            }

            .escala-card {
                flex-direction: row;
                justify-content: space-between;
            }
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
            <i class="fas fa-plus"></i> Cadastrar Escala
        </button>

        <!-- Campo de busca -->
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar escala...">
        </div>

        <!-- Cards de escalas -->
        <div id="escalasList" class="row">
            <!-- Cards serão carregados aqui via AJAX -->
        </div>
    </main>

    <!-- Modal para cadastrar escala -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Cadastrar Escala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createEscalaForm">
                    <div class="mb-3">
                        <label for="createCultoId" class="form-label">Culto</label>
                        <select class="form-control" id="createCultoId" required>
                            <!-- Opções serão carregadas via AJAX -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="createUsuarioId" class="form-label">Musico</label>
                        <select class="form-control" id="createUsuarioId" required>
                            <!-- Opções serão carregadas via AJAX -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="createInstrumento" class="form-label">Instrumento</label>
                        <input type="text" class="form-control" id="createInstrumento" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Modal para editar escala -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Escala</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEscalaForm">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editCultoId" class="form-label">Culto</label>
                            <select class="form-control" id="editCultoId" required>
                                <!-- Opções serão carregadas via AJAX -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editUsuarioId" class="form-label">Músico</label>
                            <select class="form-control" id="editUsuarioId" required>
                                <!-- Opções serão carregadas via AJAX -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editInstrumento" class="form-label">Instrumento</label>
                            <input type="text" class="form-control" id="editInstrumento" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para excluir escala -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" style="display: block;" inert>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Excluir Escala</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza de que deseja excluir esta escala?</p>
                    <button type="button" id="deleteConfirm" class="btn btn-danger w-100">Excluir</button>
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
    function carregarEscalas() {
        $.ajax({
            url: '../../controllers/EscalaController.php',
            method: 'GET',
            data: { action: 'listar' },
            dataType: 'json',
            success: function(response) {
                console.log(response);  // Para depurar e ver o formato da resposta
                if (response.status === "error") {
                    alert(response.message);
                    return;
                }
                if (response.status === "success" && Array.isArray(response.data)) {
                    let escalaCards = response.data.map(escala => `
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="escala-card">
                                <h5>${escala.culto_titulo}</h5>
                                <p><strong>Músico:</strong> ${escala.usuario_nome}</p>
                                <p><strong>Instrumento:</strong> ${escala.instrumento}</p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-info btn-sm flex-grow-1 edit-btn" data-id="${escala.id}">Editar</button>
                                    <button class="btn btn-danger btn-sm flex-grow-1 delete-btn" data-id="${escala.id}">Excluir</button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    $('#escalasList').html(escalaCards);
                } else {
                    console.error("Erro: resposta inesperada", response);
                    alert("Erro ao carregar escalas.");
                }
            },
            error: function(xhr) {
                console.error("Erro AJAX:", xhr.responseText);
                alert('Erro na comunicação com o servidor.');
            }
        });
    }
        // Função para carregar cultos
        function carregarCultos() {
        $.ajax({
            url: '../../controllers/CultoController.php',
            method: 'GET',
            data: { action: 'listar' },
            dataType: 'json',
            success: function(response) {
                if (response.status === "success" && Array.isArray(response.data)) {
                    let options = response.data.map(culto => `
                        <option value="${culto.id}">${culto.titulo}</option>
                    `).join('');
                    $('#createCultoId').html(options);
                } else {
                    console.error("Erro ao carregar cultos:", response.message);
                }
            },
            error: function(xhr) {
                console.error("Erro AJAX ao carregar cultos:", xhr.responseText);
            }
        });
    }

    // Função para carregar usuários
    function carregarUsuarios() {
        $.ajax({
            url: '../../controllers/UsuarioController.php',
            method: 'GET',
            data: { action: 'listar' },
            dataType: 'json',
            success: function(response) {
                if (response.status === "success" && Array.isArray(response.data)) {
                    let options = response.data.map(usuario => `
                        <option value="${usuario.id}">${usuario.nome}</option>
                    `).join('');
                    $('#createUsuarioId').html(options);
                } else {
                    console.error("Erro ao carregar usuários:", response.message);
                }
            },
            error: function(xhr) {
                console.error("Erro AJAX ao carregar usuários:", xhr.responseText);
            }
        });
    }

    // Quando o modal de cadastro for aberto, carregar cultos e usuários
    $('#createModal').on('show.bs.modal', function () {
        carregarCultos();
        carregarUsuarios();
    });
    $('#createEscalaForm').on('submit', function (e) {
        e.preventDefault();
        let escalaData = {
            action: 'cadastrar',
            culto_id: $('#createCultoId').val(),
            usuario_id: $('#createUsuarioId').val(),
            instrumento: $('#createInstrumento').val()
        };

        $.ajax({
            url: '../../controllers/EscalaController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(escalaData),
            dataType: 'json',
            success: function(response) {
                console.log(response);  // Depuração para ver a resposta do servidor
                alert(response.message);  // Mensagem do servidor
                if (response.status === "success") {
                    $('#createModal').modal('hide');
                    $('#createEscalaForm')[0].reset();
                    carregarEscalas();
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
            url: '../../controllers/EscalaController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'editar', id: id }),
            dataType: 'json',
            success: function(response) {
                console.log(response);  // Depuração para ver a resposta do servidor
                if (response.status === "error") {
                    alert(response.message);
                    return;
                }
                if (response.status === "success" && response.data) {
                    $('#editId').val(response.data.id);
                    $('#editCultoId').val(response.data.culto_id);
                    $('#editUsuarioId').val(response.data.usuario_id);
                    $('#editInstrumento').val(response.data.instrumento);
                    $('#editModal').modal('show');
                } else {
                    alert("Erro ao carregar os dados da escala.");
                }
            },
            error: function(xhr) {
                console.error("Erro ao buscar escala:", xhr.responseText);
                alert('Erro ao buscar dados da escala.');
            }
        });
    });

    $('#editEscalaForm').on('submit', function (e) {
        e.preventDefault();
        let escalaData = {
            action: 'atualizar',
            id: $('#editId').val(),
            culto_id: $('#editCultoId').val(),
            usuario_id: $('#editUsuarioId').val(),
            instrumento: $('#editInstrumento').val()
        };

        $.ajax({
            url: '../../controllers/EscalaController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(escalaData),
            dataType: 'json',
            success: function(response) {
                console.log(response);  // Depuração para ver a resposta do servidor
                alert(response.message);  // Mensagem do servidor
                if (response.status === "success") {
                    $('#editModal').modal('hide');
                    carregarEscalas();
                }
            },
            error: function(xhr) {
                console.error("Erro ao atualizar:", xhr.responseText);
                alert('Erro ao atualizar a escala.');
            }
        });
    });

    $(document).on('click', '.delete-btn', function () {
        let id = $(this).data('id');
        if (!confirm("Tem certeza que deseja excluir esta escala?")) return;

        $.ajax({
            url: '../../controllers/EscalaController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'deletar', id: id }),
            dataType: 'json',
            success: function(response) {
                console.log(response);  // Depuração para ver a resposta do servidor
                alert(response.message);  // Mensagem do servidor
                if (response.status === "success") {
                    carregarEscalas();
                }
            },
            error: function(xhr) {
                console.error("Erro ao excluir:", xhr.responseText);
                alert('Erro ao excluir a escala.');
            }
        });
    });

    carregarEscalas();
});
</script>
</body>
</html>