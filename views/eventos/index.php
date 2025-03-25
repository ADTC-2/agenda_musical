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
    <title>Eventos</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="../../assets/img/logoadtc2__new.png" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        .evento-card {
            border: 1px solid #ddd;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .evento-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .evento-card h5 {
            margin: 0 0 10px 0;
            font-size: 1.5em;
            color: #333;
        }

        .evento-card p {
            margin: 5px 0;
            color: #555;
        }

        .evento-card .btn {
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .table-responsive {
                display: none;
            }

            .evento-card {
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
                                <a class="nav-link active" href="../dashboard.php">
                                    <i class="fas fa-home"></i> Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="../cultos/index.php">
                                    <i class="fas fa-home"></i> Cultos
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
            <i class="fas fa-plus"></i> Cadastrar Evento
        </button>

        <!-- Campo de busca -->
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar evento...">
        </div>

        <!-- Cards de eventos -->
        <div id="eventosList" class="row">
            <!-- Cards serão carregados aqui via AJAX -->
        </div>
    </main>

    <!-- Modal para cadastrar evento -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Criar Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createEventoForm">
                        <div class="mb-3">
                            <label for="createNome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="createNome" required>
                        </div>
                        <div class="mb-3">
                            <label for="createDataHora" class="form-label">Data e Hora</label>
                            <input type="datetime-local" class="form-control" id="createDataHora" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Criar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar evento -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEventoForm">
                    <input type="hidden" id="editId">
                    <div class="mb-3">
                        <label for="editNome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="editNome" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDataHora" class="form-label">Data e Hora</label>
                        <input type="datetime-local" class="form-control" id="editDataHora" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Modal para excluir evento -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Excluir Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza de que deseja excluir este evento?</p>
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
    // Função para carregar eventos via AJAX
    function carregarEventos() {
    $.ajax({
        url: '../../controllers/EventoController.php',
        method: 'GET',
        data: { action: 'listar' },
        dataType: 'json',
        success: function(response) {
            if (response.status === "error") {
                alert(response.message);
                return;
            }
            if (response.status === "success" && Array.isArray(response.data)) {
                let eventoCards = response.data.map(evento => {
                    let dataObj = new Date(evento.data_hora);
                    let dataFormatada = dataObj.toLocaleDateString('pt-BR');
                    let horaFormatada = dataObj.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });

                    return `
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="evento-card">
                                <div class="row">
                                    <div class="col-7">
                                        <h5 class="mb-0">${evento.nome}</h5>
                                    </div>
                                    <div class="col-5 text-end">
                                        <small class="text-muted">
                                            ${dataFormatada}<br>
                                            ${horaFormatada}
                                        </small>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex gap-2">                                            
                                    <a href="../repertorio/index.php" class="btn btn-danger btn-sm flex-grow-1">
                                        <i class="fas fa-music me-1"></i> Repertório
                                    </a>
                                    <button class="btn btn-warning btn-sm flex-grow-1 btn-editar" data-id="${evento.id}" data-nome="${evento.nome}" data-data="${evento.data_hora}">
                                        <i class="fas fa-edit me-1"></i> 
                                    </button>
                                    <button class="btn btn-danger btn-sm flex-grow-1 btn-excluir" data-id="${evento.id}">
                                        <i class="fas fa-trash-alt me-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
                $('#eventosList').html(eventoCards);
            } else {
                console.error("Erro: resposta inesperada", response);
                alert("Erro ao carregar eventos.");
            }
        },
        error: function(xhr) {
            console.error("Erro AJAX:", xhr.responseText);
            alert('Erro na comunicação com o servidor.');
        }
    });
}

    // Evento de clique para abrir o modal de edição
    $(document).on('click', '.btn-editar', function() {
        let id = $(this).data('id');
        let nome = $(this).data('nome');
        let dataHora = $(this).data('data');
        
        $('#editId').val(id);
        $('#editNome').val(nome);
        $('#editDataHora').val(dataHora);
        
        $('#editModal').modal('show');
    });

    // Evento de clique para abrir o modal de exclusão
    $(document).on('click', '.btn-excluir', function() {
        let id = $(this).data('id');
        $('#deleteConfirm').data('id', id);
        $('#deleteModal').modal('show');
    });

    // Submissão do formulário de cadastro de evento
    $('#createEventoForm').on('submit', function (e) {
        e.preventDefault();
        let eventoData = {
            action: 'cadastrar',
            nome: $('#createNome').val(),
            data_hora: $('#createDataHora').val()
        };

        $.ajax({
            url: '../../controllers/EventoController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(eventoData),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.status === "success") {
                    $('#createModal').modal('hide');
                    $('#createEventoForm')[0].reset();
                    carregarEventos();
                }
            },
            error: function(xhr) {
                console.error("Erro ao cadastrar:", xhr.responseText);
                alert('Erro ao cadastrar.');
            }
        });
    });

    // Submissão do formulário de edição de evento
    $('#editEventoForm').on('submit', function (e) {
        e.preventDefault();
        let eventoData = {
            action: 'atualizar',
            id: $('#editId').val(),
            nome: $('#editNome').val(),
            data_hora: $('#editDataHora').val()
        };

        $.ajax({
            url: '../../controllers/EventoController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(eventoData),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.status === "success") {
                    $('#editModal').modal('hide');
                    carregarEventos();
                }
            },
            error: function(xhr) {
                console.error("Erro ao atualizar:", xhr.responseText);
                alert('Erro ao atualizar o evento.');
            }
        });
    });

    // Excluir evento com confirmação
    $('#deleteConfirm').on('click', function() {
        let id = $(this).data('id');

        $.ajax({
            url: '../../controllers/EventoController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'deletar', id: id }),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.status === "success") {
                    $('#deleteModal').modal('hide');
                    carregarEventos();
                }
            },
            error: function(xhr) {
                console.error("Erro ao excluir evento:", xhr.responseText);
                alert('Erro ao excluir evento.');
            }
        });
    });

    // Carregar eventos na inicialização
    carregarEventos();
});
</script>
</body>

</html>