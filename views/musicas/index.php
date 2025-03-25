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
    <title>Músicas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="../../assets/img/logoadtc2__new.png" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
    .musica-card {
        border: 1px solid #ddd;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .musica-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .musica-card h5 {
        margin: 0 0 10px 0;
        font-size: 1.5em;
        color: #333;
    }

    .musica-card p {
        margin: 5px 0;
        color: #555;
    }

    .musica-card .btn {
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .table-responsive {
            display: none;
        }

        .musica-card {
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
            <i class="fas fa-plus"></i> Adicionar Música
        </button>
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar música...">
        </div>
        <div id="musicasList" class="row">
            <!-- Cards serão carregados aqui via AJAX -->
        </div>
    </main>

    <!-- Modal para cadastrar música -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Adicionar Música</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form id="createMusicaForm" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="createTitulo" class="form-label">Título</label>
        <input type="text" class="form-control" id="createTitulo" name="titulo" required>
    </div>
    <div class="mb-3">
        <label for="createCantorBanda" class="form-label">Cantor/Banda</label>
        <input type="text" class="form-control" id="createCantorBanda" name="cantor_banda">
    </div>
    <div class="mb-3">
        <label for="createTipo" class="form-label">Tipo</label>
        <select class="form-control" id="createTipo" name="tipo" required>
            <option value="Louvor">Louvor</option>
            <option value="Harpa Cristã">Harpa Cristã</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="createTom" class="form-label">Tom</label>
        <input type="text" class="form-control" id="createTom" name="tom">
    </div>
    <div class="mb-3">
        <label for="createBpm" class="form-label">BPM</label>
        <input type="number" class="form-control" id="createBpm" name="bpm">
    </div>
    <div class="mb-3">
        <label for="createLink" class="form-label">Link</label>
        <input type="url" class="form-control" id="createLink" name="link">
    </div>
    <div class="mb-3">
        <label for="createArquivo" class="form-label">Arquivo</label>
        <input type="file" class="form-control" id="createArquivo" name="arquivo">
    </div>
    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
</form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar música -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Música</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editMusicaForm">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editTitulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="editTitulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCantorBanda" class="form-label">Cantor/Banda</label>
                            <input type="text" class="form-control" id="editCantorBanda">
                        </div>
                        <div class="mb-3">
                            <label for="editTipo" class="form-label">Tipo</label>
                            <select class="form-control" id="editTipo" required>
                                <option value="Louvor">Louvor</option>
                                <option value="Harpa Cristã">Harpa Cristã</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editTom" class="form-label">Tom</label>
                            <input type="text" class="form-control" id="editTom">
                        </div>
                        <div class="mb-3">
                            <label for="editBpm" class="form-label">BPM</label>
                            <input type="number" class="form-control" id="editBpm">
                        </div>
                        <div class="mb-3">
                            <label for="editLink" class="form-label">Link</label>
                            <input type="url" class="form-control" id="editLink">
                        </div>
                        <div class="mb-3">
                            <label for="editArquivo" class="form-label">Arquivo</label>
                            <input type="file" class="form-control" id="editArquivo">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para excluir música -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Excluir Música</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza de que deseja excluir esta música?</p>
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
    // Função para carregar músicas
    function carregarMusicas() {
        $.ajax({
            url: '../../controllers/MusicaController.php',
            method: 'POST',
            data: { action: 'listar' },
            dataType: 'json',
            success: function(response) {
                if (response.status === "success") {
                    $('#musicasList').empty();
                    if (response.data.length > 0) {
                        response.data.forEach(function(musica) {
                            $('#musicasList').append(`
                                <div class="col-md-4">
                                    <div class="musica-card">
                                        <h5>${musica.titulo}</h5>
                                        <p><strong>Cantor/Banda:</strong> ${musica.cantor_banda}</p>
                                        <p><strong>Tipo:</strong> ${musica.tipo}</p>
                                        <p><strong>Tom:</strong> ${musica.tom}</p>
                                        <p><strong>BPM:</strong> ${musica.bpm}</p>
                                        <button class="btn btn-warning edit-btn" data-id="${musica.id}">Editar</button>
                                        <button class="btn btn-danger delete-btn" data-id="${musica.id}">Excluir</button>
                                    </div>
                                </div>
                            `);
                        });
                    } else {
                        $('#musicasList').html('<p class="text-center">Nenhuma música encontrada.</p>');
                    }
                } else {
                    Swal.fire('Erro', response.message || 'Erro ao carregar músicas.', 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Erro', 'Erro ao carregar músicas: ' + error, 'error');
            }
        });
    }

    // Carregar músicas ao abrir a página
    carregarMusicas();

    // Cadastrar música
    $('#createMusicaForm').on('submit', function(e) {
        e.preventDefault();

        // Cria um objeto FormData com os dados do formulário
        let formData = new FormData(this);
        formData.append('action', 'cadastrar'); // Adiciona a ação ao FormData

        // Envia os dados via AJAX
        $.ajax({
            url: '../../controllers/MusicaController.php',
            method: 'POST',
            data: formData,
            processData: false, // Impede o jQuery de processar os dados
            contentType: false, // Impede o jQuery de definir o tipo de conteúdo
            dataType: 'json',
            success: function(response) {
                // Exibe a mensagem de sucesso ou erro
                Swal.fire({
                    icon: response.status === "success" ? 'success' : 'error',
                    title: response.status === "success" ? 'Sucesso!' : 'Erro',
                    text: response.message
                });

                // Fecha o modal e recarrega a lista de músicas se o cadastro for bem-sucedido
                if (response.status === "success") {
                    $('#createModal').modal('hide');
                    carregarMusicas();
                }
            },
            error: function(xhr, status, error) {
                // Exibe uma mensagem de erro em caso de falha na requisição
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro ao cadastrar música: ' + error
                });
            }
        });
    });

    // Editar música
    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        $.ajax({
            url: '../../controllers/MusicaController.php',
            method: 'POST',
            data: { action: 'buscar', id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === "success") {
                    $('#editId').val(response.data.id);
                    $('#editTitulo').val(response.data.titulo);
                    $('#editCantorBanda').val(response.data.cantor_banda);
                    $('#editTipo').val(response.data.tipo);
                    $('#editTom').val(response.data.tom);
                    $('#editBpm').val(response.data.bpm);
                    $('#editLink').val(response.data.link);
                    $('#editArquivo').val(response.data.arquivo);
                    $('#editModal').modal('show');
                } else {
                    Swal.fire('Erro', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Erro', 'Erro ao carregar dados da música: ' + error, 'error');
            }
        });
    });

    // Atualizar música
    $('#editMusicaForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append('action', 'editar');
        formData.append('id', $('#editId').val());

        $.ajax({
            url: '../../controllers/MusicaController.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                Swal.fire(response.status === "success" ? 'Sucesso!' : 'Erro', response.message, response.status === "success" ? 'success' : 'error');
                if (response.status === "success") {
                    $('#editModal').modal('hide');
                    carregarMusicas();
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Erro', 'Erro ao atualizar música: ' + error, 'error');
            }
        });
    });

    // Excluir música
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
                    url: '../../controllers/MusicaController.php',
                    method: 'POST',
                    data: { action: 'excluir', id: id },
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire(response.status === "success" ? 'Sucesso!' : 'Erro', response.message, response.status === "success" ? 'success' : 'error');
                        if (response.status === "success") {
                            carregarMusicas();
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Erro', 'Erro ao excluir música: ' + error, 'error');
                    }
                });
            }
        });
    });
});
    </script>
</body>

</html>