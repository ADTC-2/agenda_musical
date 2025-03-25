<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}
// Capturar o ID do evento da URL
$evento_id = isset($_GET['evento_id']) ? (int)$_GET['evento_id'] : 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repertórios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="../../assets/img/logoadtc2__new.png" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
    .repertorio-card {
        border: 1px solid #ddd;
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .repertorio-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .repertorio-card h5 {
        margin: 0 0 10px 0;
        font-size: 1.25em;
        color: #333;
    }

    .repertorio-card p {
        margin: 5px 0;
        color: #555;
        font-size: 0.9em;
    }

    .repertorio-card .btn {
        margin-top: 10px;
        font-size: 0.8rem;
        padding: 0.35rem 0.5rem;
    }

    .musicas-list {
        max-height: 200px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .musica-item {
        display: block;
        text-decoration: none;
        color: #333;
        padding: 8px;
        margin-bottom: 5px;
        border-radius: 4px;
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }

    .musica-item:hover {
        background-color: #f8f9fa;
        border-left: 3px solid #dc3545;
    }

    #searchInput {
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        width: 100%;
    }

    /* Ajustes para mobile */
    @media (max-width: 768px) {
        .repertorio-card {
            padding: 12px;
        }
        
        .repertorio-card h5 {
            font-size: 1.1em;
        }
        
        .btn-group-mobile {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .btn-group-mobile .btn {
            flex: 1 1 45%;
            font-size: 0.75rem;
        }
        
        .musicas-list {
            max-height: 150px;
        }
    }

    /* Ajustes para desktop */
    @media (min-width: 992px) {
        .repertorio-card {
            padding: 20px;
        }
        
        #searchInput {
            max-width: 400px;
        }
    }

    /* Melhorias para cards */
    .card-body {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .buttons-container {
        margin-top: auto;
        padding-top: 15px;
    }
    
    /* Estilo para scrollbar */
    .musicas-list::-webkit-scrollbar {
        width: 5px;
    }
    
    .musicas-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .musicas-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .musicas-list::-webkit-scrollbar-thumb:hover {
        background: #555;
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
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            aria-label="Fechar"></button>
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
                                <a class="nav-link active" href="../repertorio/repertorio_musica_view.php">
                                    <i class="fas fa-home"></i> Repertório de músicas
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
        <div class="text-center mb-4">
            <img src="../../assets/img/logo_vermelho.png" alt="Louvor - Agenda Musical" class="img-fluid"
                id="logo_vermelho" style="max-height: 80px;">
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus"></i> Criar Repertório
            </button>
            <div class="w-100 w-md-auto">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar repertório...">
            </div>
        </div>
        <div id="repertoriosList" class="row">
            <!-- Cards serão carregados aqui via AJAX -->
        </div>
    </main>

    <!-- Modal para cadastrar repertório -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Criar Repertório</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createRepertorioForm">
                        <div class="mb-3">
                            <label for="createEventoId" class="form-label">Evento</label>
                            <select class="form-control" id="createEventoId" required></select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar repertório -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Repertório</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRepertorioForm">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editEventoId" class="form-label">Evento</label>
                            <select class="form-control" id="editEventoId" required></select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        function formatarData(dataString) {
            if (!dataString) return 'Data não definida';
            
            try {
                const data = new Date(dataString);
                
                if (isNaN(data.getTime())) {
                    return 'Data inválida';
                }
                
                const dia = String(data.getDate()).padStart(2, '0');
                const mes = String(data.getMonth() + 1).padStart(2, '0');
                const ano = data.getFullYear();
                const horas = String(data.getHours()).padStart(2, '0');
                const minutos = String(data.getMinutes()).padStart(2, '0');
                
                return `${dia}/${mes}/${ano} ${horas}:${minutos}`;
            } catch (e) {
                console.error('Erro ao formatar data:', e);
                return 'Data inválida';
            }
        }    
        
        // Função para carregar eventos
        function carregarEventos() {
            $.ajax({
                url: '../../controllers/RepertorioController.php',
                method: 'POST',
                data: {
                    action: 'listarEventos'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success" && Array.isArray(response.data)) {
                        $('#createEventoId, #editEventoId').empty();
                        response.data.forEach(function(evento) {
                            $('#createEventoId, #editEventoId').append(
                                `<option value="${evento.id}">${evento.nome}</option>`
                            );
                        });
                    } else {
                        Swal.fire('Erro', 'Dados de eventos inválidos.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Erro', 'Erro ao carregar eventos: ' + error, 'error');
                }
            });
        }

        function carregarRepertorios() {
            $.ajax({
                url: '../../controllers/RepertorioController.php',
                method: 'POST',
                data: { 
                    action: 'listar',
                    with_musicas: true
                },
                dataType: 'json',
                success: function(response) {
                    $('#repertoriosList').empty();

                    if (response.status === "success") {
                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function(repertorio) {
                                let musicasHTML = '';
                                
                                if (repertorio.musicas && repertorio.musicas.length > 0) {
                                    musicasHTML = `
                                    <div class="mt-3">
                                        <h6 class="mb-2 text-primary d-flex justify-content-between align-items-center">
                                            <span>Músicas Associadas</span>
                                            <span class="badge bg-primary rounded-pill">${repertorio.musicas.length}</span>
                                        </h6>
                                        <div class="musicas-list">`;
                                    
                                    repertorio.musicas.forEach(function(musica) {
                                        const titulo = musica.titulo || 'Música sem título';
                                        const categoria = musica.categoria ? `<span class="badge bg-light text-dark ms-2">${musica.categoria}</span>` : '';
                                        const musicaId = musica.id || '';
                                        
                                        musicasHTML += `
                                            <a href="../musicas/detalhes.php?id=${musicaId}" class="musica-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-music me-2 text-muted"></i>${titulo}</span>
                                                    ${categoria}
                                                </div>
                                            </a>`;
                                    });
                                    
                                    musicasHTML += '</div></div>';
                                } else {
                                    musicasHTML = `
                                    <div class="mt-3 text-center py-2 bg-light rounded">
                                        <small class="text-muted">Nenhuma música associada</small>
                                    </div>`;
                                }

                                const dataEvento = repertorio.evento_data ? formatarData(repertorio.evento_data) : 'Data não definida';
                                
                                // Verifica se é mobile para ajustar o layout dos botões
                                const isMobile = window.innerWidth <= 768;
                                const buttonsHTML = isMobile ? `
                                    <div class="buttons-container btn-group-mobile">
                                        <a href="../musicas/index.php" class="btn btn-danger btn-sm">
                                            <i class="fas fa-plus"></i> Criar
                                        </a>
                                        <a href="../repertorio/repertorio_musica_view.php?repertorio_id=${repertorio.id || ''}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-link"></i> Associar
                                        </a>
                                        <a href="../musicas/index.php" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-search"></i> Buscar
                                        </a>
                                    </div>
                                ` : `
                                    <div class="buttons-container">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <a href="../musicas/index.php" class="btn btn-danger btn-sm w-100">
                                                    <i class="fas fa-plus"></i> Criar
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="../repertorio/buscaPorRepertorio.php?repertorio_id=${repertorio.id || ''}" class="btn btn-primary btn-sm w-100">
                                                    <i class="fas fa-link"></i> Associar
                                                </a>
                                            </div>
                                            <div class="col-12">
                                                <a href="../musicas/index.php" class="btn btn-outline-secondary btn-sm w-100">
                                                    <i class="fas fa-search"></i> Buscar Músicas
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                
                                $('#repertoriosList').append(`
                                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                                        <div class="repertorio-card">
                                            <div>
                                                <h5 class="text-truncate">${repertorio.evento_nome || 'Repertório sem nome'}</h5>
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>${dataEvento}</small>
                                                    <span class="badge bg-secondary">ID: ${repertorio.id || ''}</span>
                                                </div>
                                                ${musicasHTML}
                                            </div>
                                            ${buttonsHTML}
                                        </div>
                                    </div>
                                `);
                            });
                        } else {
                            $('#repertoriosList').html('<div class="col-12"><div class="alert alert-info text-center py-4">Nenhum repertório cadastrado ainda.</div></div>');
                        }
                    } else {
                        console.error('Erro ao carregar repertórios:', response.message);
                        $('#repertoriosList').html('<div class="col-12"><div class="alert alert-danger text-center py-4">Erro ao carregar repertórios.</div></div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', error);
                    $('#repertoriosList').html('<div class="col-12"><div class="alert alert-danger text-center py-4">Erro na conexão com o servidor.</div></div>');
                }
            });
        }

        // Carregar eventos e repertórios ao abrir a página
        carregarEventos();
        carregarRepertorios();

        // Redimensionar tela - ajustar layout
        $(window).resize(function() {
            carregarRepertorios();
        });

        // Cadastrar repertório
        $('#createRepertorioForm').on('submit', function(e) {
            e.preventDefault();
            let eventoId = $('#createEventoId').val();
            if (!eventoId) {
                Swal.fire('Erro', 'Por favor, selecione um evento.', 'error');
                return;
            }
            let repertorioData = {
                action: 'cadastrar',
                evento_id: eventoId
            };
            $.ajax({
                url: '../../controllers/RepertorioController.php',
                method: 'POST',
                data: repertorioData,
                dataType: 'json',
                success: function(response) {
                    Swal.fire(response.status === "success" ? 'Sucesso!' : 'Erro', response
                        .message, response.status === "success" ? 'success' : 'error');
                    if (response.status === "success") {
                        $('#createModal').modal('hide');
                        carregarRepertorios();
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Erro', 'Erro ao cadastrar repertório: ' + error, 'error');
                }
            });
        });

        // Editar repertório
        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '../../controllers/RepertorioController.php',
                method: 'POST',
                data: {
                    action: 'editar',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        $('#editId').val(response.data.id);
                        $('#editEventoId').val(response.data.evento_id);
                        $('#editModal').modal('show');
                    } else {
                        Swal.fire('Erro', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Erro', 'Erro ao carregar dados do repertório: ' + error,
                        'error');
                }
            });
        });

        // Atualizar repertório
        $('#editRepertorioForm').on('submit', function(e) {
            e.preventDefault();
            let repertorioData = {
                action: 'atualizar',
                id: $('#editId').val(),
                evento_id: $('#editEventoId').val()
            };
            $.ajax({
                url: '../../controllers/RepertorioController.php',
                method: 'POST',
                data: repertorioData,
                dataType: 'json',
                success: function(response) {
                    Swal.fire(response.status === "success" ? 'Sucesso!' : 'Erro', response
                        .message, response.status === "success" ? 'success' : 'error');
                    if (response.status === "success") {
                        $('#editModal').modal('hide');
                        carregarRepertorios();
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Erro', 'Erro ao atualizar repertório: ' + error, 'error');
                }
            });
        });

        // Excluir repertório
        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../controllers/RepertorioController.php',
                        method: 'POST',
                        data: {
                            action: 'deletar',
                            id: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            Swal.fire(response.status === "success" ? 'Sucesso!' :
                                'Erro', response.message, response.status ===
                                "success" ? 'success' : 'error');
                            if (response.status === "success") {
                                carregarRepertorios();
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Erro', 'Erro ao excluir repertório: ' +
                                error, 'error');
                        }
                    });
                }
            });
        });
        
        // Filtro de busca
        $('#searchInput').on('keyup', function() {
            const searchText = $(this).val().toLowerCase();
            $('.repertorio-card').each(function() {
                const cardText = $(this).text().toLowerCase();
                $(this).closest('.col-12').toggle(cardText.includes(searchText));
            });
        });
    });
    </script>
</body>

</html>