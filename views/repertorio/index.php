<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}
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
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .repertorio-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .repertorio-card h5 {
        margin: 0 0 10px 0;
        font-size: 1.5em;
        color: #333;
    }

    .repertorio-card p {
        margin: 5px 0;
        color: #555;
    }

    .repertorio-card .btn {
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .table-responsive {
            display: none;
        }

        .repertorio-card {
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
                id="logo_vermelho">
        </div>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Criar Repertório
        </button>
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar repertório...">
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

    <!-- Modal para excluir repertório -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Excluir Repertório</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza de que deseja excluir este repertório?</p>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        function formatarData(dataString) {
    if (!dataString) return 'Data não definida';
    
    try {
        const data = new Date(dataString);
        
        // Verifica se a data é válida
        if (isNaN(data.getTime())) {
            return 'Data inválida';
        }
        
        // Formata para dd/mm/aaaa hh:mm
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
                    // Verifica se a resposta é válida
                    if (response.status === "success" && Array.isArray(response.data)) {
                        // Limpa os selects antes de adicionar novas opções
                        $('#createEventoId, #editEventoId').empty();

                        // Adiciona cada evento como uma opção no select
                        response.data.forEach(function(evento) {
                            $('#createEventoId, #editEventoId').append(
                                `<option value="${evento.id}">${evento.nome}</option>`
                            );
                        });
                    } else {
                        // Exibe um erro se os dados forem inválidos
                        Swal.fire('Erro', 'Dados de eventos inválidos.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    // Exibe um erro se a requisição falhar
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
                        // Construir lista de músicas
                        let musicasHTML = '';
                        
                        if (repertorio.musicas && repertorio.musicas.length > 0) {
                            musicasHTML = `
                            <div class="mt-3">
                                <h6 class="mb-2 text-primary d-flex justify-content-between align-items-center">
                                    <span>Músicas Associadas</span>
                                    <span class="badge bg-primary rounded-pill">${repertorio.musicas.length}</span>
                                </h6>
                                <div class="musicas-list" style="max-height: 200px; overflow-y: auto;">`;
                            
                            repertorio.musicas.forEach(function(musica) {
                                const titulo = musica.titulo || 'Música sem título';
                                const categoria = musica.categoria ? `<span class="badge bg-light text-dark ms-2">${musica.categoria}</span>` : '';
                                const musicaId = musica.id || '';
                                
                                musicasHTML += `
                                    <a href="../musicas/detalhes.php?id=${musicaId}" class="musica-item d-block text-decoration-none text-dark p-2 mb-1 rounded hover-effect">
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
                        
                        // Cria o card do repertório com TODOS os botões DENTRO do card
                        $('#repertoriosList').append(`
                            <div class="col-md-4 mb-4">
                                <div class="repertorio-card card h-100 shadow-sm">
                                    <div class="card-header bg-white">
                                        <h5 class="card-title mb-0 text-truncate">${repertorio.evento_nome || 'Repertório sem nome'}</h5>
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>${dataEvento}</small>
                                            <span class="badge bg-secondary">ID: ${repertorio.id || ''}</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        ${musicasHTML}
                                        
                                        <!-- Área dos botões DENTRO do card-body -->
                                        <div class="mt-3 pt-2 border-top">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <a href="../musicas/index.php" class="btn btn-danger btn-sm w-100">
                                                        <i class="fas fa-plus"></i> Criar
                                                    </a>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="../repertorio/repertorio_musica_view.php?repertorio_id=${repertorio.id || ''}" class="btn btn-primary btn-sm w-100">
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
                                    </div>
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

// CSS adicional para melhorar a aparência
const estiloAdicional = `
<style>
    .repertorio-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .repertorio-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .card-header {
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .musica-item.hover-effect {
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }
    
    .musica-item.hover-effect:hover {
        background-color: #f8f9fa;
        border-left: 3px solid var(--bs-primary);
        transform: translateX(3px);
    }
    
    .musicas-list {
        scrollbar-width: thin;
        scrollbar-color: #ddd #f8f9fa;
        padding-right: 5px;
    }
    
    .musicas-list::-webkit-scrollbar {
        width: 6px;
    }
    
    .musicas-list::-webkit-scrollbar-track {
        background: #f8f9fa;
        border-radius: 6px;
    }
    
    .musicas-list::-webkit-scrollbar-thumb {
        background-color: #adb5bd;
        border-radius: 6px;
    }
    
    .card-body .btn {
        font-size: 0.8rem;
        padding: 0.35rem 0.5rem;
    }
    
    .card-body .btn i {
        margin-right: 5px;
    }
</style>
`;

$('head').append(estiloAdicional);
// Carregar eventos e repertórios ao abrir a página
        carregarEventos();
        carregarRepertorios();

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
    });
    </script>
</body>

</html>