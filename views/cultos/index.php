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
    <title>Cultos</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="../../assets/img/logoadtc2__new.png" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        /* Estilo semelhante ao modelo fornecido */
        .culto-card {
            border: 1px solid #ddd;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .culto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .culto-card h5 {
            margin: 0 0 10px 0;
            font-size: 1.5em;
            color: #333;
        }

        .culto-card p {
            margin: 5px 0;
            color: #555;
        }

        .culto-card .btn {
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .table-responsive {
                display: none;
            }

            .culto-card {
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
            <i class="fas fa-plus"></i> Cadastrar Culto
        </button>

        <!-- Campo de busca -->
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar culto...">
        </div>

        <!-- Cards de cultos -->
        <div id="cultosList" class="row">
            <!-- Cards serão carregados aqui via AJAX -->
        </div>
    </main>

    <!-- Modal para cadastrar culto -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Cadastrar Culto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createCultoForm">
                        <div class="mb-3">
                            <label for="createTitulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="createTitulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="createDataHora" class="form-label">Data e Hora</label>
                            <input type="datetime-local" class="form-control" id="createDataHora" required>
                        </div>
                        <div class="mb-3">
                            <label for="createLocal" class="form-label">Local</label>
                            <input type="text" class="form-control" id="createLocal">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar culto -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Culto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCultoForm">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editTitulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="editTitulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDataHora" class="form-label">Data e Hora</label>
                            <input type="datetime-local" class="form-control" id="editDataHora" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLocal" class="form-label">Local</label>
                            <input type="text" class="form-control" id="editLocal">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para excluir culto -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" style="display: block;" inert>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Excluir Culto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza de que deseja excluir este culto?</p>
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
    function carregarCultos() {
        $.ajax({
            url: '../../controllers/CultoController.php',
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
                    let cultoCards = response.data.map(culto => `
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="culto-card">
                                <h5>${culto.titulo}</h5>
                                <p><strong>Data e Hora:</strong> ${culto.data_hora}</p>
                                <p><strong>Local:</strong> ${culto.local}</p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-info btn-sm flex-grow-1 edit-btn" data-id="${culto.id}">Editar</button>
                                    <button class="btn btn-danger btn-sm flex-grow-1 delete-btn" data-id="${culto.id}">Excluir</button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    $('#cultosList').html(cultoCards);
                } else {
                    console.error("Erro: resposta inesperada", response);
                    alert("Erro ao carregar cultos.");
                }
            },
            error: function(xhr) {
                console.error("Erro AJAX:", xhr.responseText);
                alert('Erro na comunicação com o servidor.');
            }
        });
    }

    $('#createCultoForm').on('submit', function (e) {
        e.preventDefault();
        let cultoData = {
            action: 'cadastrar',
            titulo: $('#createTitulo').val(),
            dataHora: $('#createDataHora').val(),
            local: $('#createLocal').val()
        };

        $.ajax({
            url: '../../controllers/CultoController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(cultoData),
            dataType: 'json',
            success: function(response) {
                console.log(response);  // Depuração para ver a resposta do servidor
                alert(response.message);  // Mensagem do servidor
                if (response.status === "success") {
                    $('#createModal').modal('hide');
                    $('#createCultoForm')[0].reset();
                    carregarCultos();
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
            url: '../../controllers/CultoController.php',
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
                // Verifique a resposta e use `data` para acessar as informações do culto
                if (response.status === "success" && response.data) {
                    $('#editId').val(response.data.id);
                    $('#editTitulo').val(response.data.titulo);
                    $('#editDataHora').val(response.data.data_hora);
                    $('#editLocal').val(response.data.local);
                    $('#editModal').modal('show');
                } else {
                    alert("Erro ao carregar os dados do culto.");
                }
            },
            error: function(xhr) {
                console.error("Erro ao buscar culto:", xhr.responseText);
                alert('Erro ao buscar dados do culto.');
            }
        });
    });


    $('#editCultoForm').on('submit', function (e) {
        e.preventDefault();
        let cultoData = {
            action: 'atualizar',
            id: $('#editId').val(),
            titulo: $('#editTitulo').val(),
            dataHora: $('#editDataHora').val(),
            local: $('#editLocal').val()
        };

        $.ajax({
            url: '../../controllers/CultoController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(cultoData),
            dataType: 'json',
            success: function(response) {
                console.log(response);  // Depuração para ver a resposta do servidor
                alert(response.message);  // Mensagem do servidor
                if (response.status === "success") {
                    $('#editModal').modal('hide');
                    carregarCultos();
                }
            },
            error: function(xhr) {
                console.error("Erro ao atualizar:", xhr.responseText);
                alert('Erro ao atualizar o culto.');
            }
        });
    });

    $(document).on('click', '.delete-btn', function () {
        let id = $(this).data('id');
        if (!confirm("Tem certeza que deseja excluir este culto?")) return;

        $.ajax({
            url: '../../controllers/CultoController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'deletar', id: id }),
            dataType: 'json',
            success: function(response) {
                console.log(response);  // Depuração para ver a resposta do servidor
                alert(response.message);  // Mensagem do servidor
                if (response.status === "success") {
                    carregarCultos();
                }
            },
            error: function(xhr) {
                console.error("Erro ao excluir:", xhr.responseText);
                alert('Erro ao excluir o culto.');
            }
        });
    });

    carregarCultos();
});

</script>
</body>
</html>
