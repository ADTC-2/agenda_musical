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
    <title>Músicas</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="../../assets/img/logoadtc2__new.png" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

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
            <a class="navbar-brand" href="../dashboard.php">Agenda Musical</a>

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
        <i class="fas fa-plus"></i> Cadastrar Música
    </button>

    <!-- Campo de busca -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Buscar música...">
    </div>

    <!-- Cards de músicas -->
    <div id="musicasList" class="row">
        <!-- Cards serão carregados aqui via AJAX -->
    </div>
</main>

<!-- Modal para cadastrar música -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Cadastrar Música</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createMusicaForm">
                    <div class="mb-3">
                        <label for="createTitulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="createTitulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="createCantorBanda" class="form-label">Cantor/Banda</label>
                        <input type="text" class="form-control" id="createCantorBanda">
                    </div>
                    <div class="mb-3">
                        <label for="createTom" class="form-label">Tom</label>
                        <input type="text" class="form-control" id="createTom">
                    </div>
                    <div class="mb-3">
                        <label for="createBpm" class="form-label">BPM</label>
                        <input type="number" class="form-control" id="createBpm">
                    </div>
                    <div class="mb-3">
                        <label for="createLink" class="form-label">Link</label>
                        <input type="url" class="form-control" id="createLink">
                    </div>
                    <div class="mb-3">
                        <label for="createArquivo" class="form-label">Arquivo</label>
                        <input type="text" class="form-control" id="createArquivo">
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
                        <input type="text" class="form-control" id="editArquivo">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para excluir música -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" style="display: block;" inert>
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
        <a href="../escalas/index.php" class="menu-item" aria-label="Escalas">
            <i class="fas fa-calendar-alt"></i> Escalas
        </a>
        <a href="../repertorio/index.php" class="menu-item" aria-label="Repertório">
            <i class="fas fa-book"></i> Repertório
        </a>
        <a href="../musicas/index.php" class="menu-item" aria-label="Músicas">
            <i class="fas fa-music"></i> Músicas
        </a>
        <a href="../usuarios/index.php" class="menu-item" aria-label="Usuários">
            <i class="fas fa-users"></i> Usuários
        </a>
        <a href="../avisos/index.php" class="menu-item" aria-label="Avisos">
            <i class="fas fa-bell"></i> Avisos
        </a>
    </nav>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    function carregarMusicas() {
        $.ajax({
            url: '../../controllers/MusicaController.php',
            method: 'GET',
            data: { action: 'listar' },
            dataType: 'json',
            success: function(response) {
                if (response.status === "error") {
                    alert(response.message);
                    return;
                }
                if (response.status === "success" && Array.isArray(response.data)) {
                    let musicaCards = response.data.map(musica => `
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="musica-card">
                                <h5>${musica.titulo}</h5>
                                <p><strong>Cantor/Banda:</strong> ${musica.cantor_banda}</p>
                                <p><strong>Tom:</strong> ${musica.tom}</p>
                                <p><strong>BPM:</strong> ${musica.bpm}</p>
                                <p><strong>Link:</strong> <a href="${musica.link}" target="_blank">${musica.link}</a></p>
                                <p><strong>Arquivo:</strong> ${musica.arquivo}</p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-info btn-sm flex-grow-1 edit-btn" data-id="${musica.id}">Editar</button>
                                    <button class="btn btn-danger btn-sm flex-grow-1 delete-btn" data-id="${musica.id}">Excluir</button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    $('#musicasList').html(musicaCards);
                } else {
                    console.error("Erro: resposta inesperada", response);
                    alert("Erro ao carregar músicas.");
                }
            },
            error: function(xhr) {
                console.error("Erro AJAX:", xhr.responseText);
                alert('Erro na comunicação com o servidor.');
            }
        });
    }

    $('#createMusicaForm').on('submit', function (e) {
        e.preventDefault();
        let musicaData = {
            action: 'cadastrar',
            titulo: $('#createTitulo').val(),
            cantorBanda: $('#createCantorBanda').val(),
            tom: $('#createTom').val(),
            bpm: $('#createBpm').val(),
            link: $('#createLink').val(),
            arquivo: $('#createArquivo').val()
        };

        if (!musicaData.titulo) {
            alert("O título da música é obrigatório.");
            return;
        }

        $.ajax({
            url: '../../controllers/MusicaController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(musicaData),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.status === "success") {
                    $('#createModal').modal('hide');
                    $('#createMusicaForm')[0].reset();
                    carregarMusicas();
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
            url: '../../controllers/MusicaController.php',
            method: 'POST',  // ALTERADO para POST
            data: JSON.stringify({ action: 'buscar', id: id }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response.status === "success") {
                    $('#editId').val(response.data.id);
                    $('#editTitulo').val(response.data.titulo);
                    $('#editCantorBanda').val(response.data.cantor_banda);
                    $('#editTom').val(response.data.tom);
                    $('#editBpm').val(response.data.bpm);
                    $('#editLink').val(response.data.link);
                    $('#editArquivo').val(response.data.arquivo);
                    $('#editModal').modal('show');
                } else {
                    alert('Erro ao carregar dados para edição');
                }
            }
        });
    });

    $('#editMusicaForm').on('submit', function (e) {
        e.preventDefault();
        let musicaData = {
            action: 'editar',
            id: $('#editId').val(),
            titulo: $('#editTitulo').val(),
            cantorBanda: $('#editCantorBanda').val(),
            tom: $('#editTom').val(),
            bpm: $('#editBpm').val(),
            link: $('#editLink').val(),
            arquivo: $('#editArquivo').val()
        };

        $.ajax({
            url: '../../controllers/MusicaController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(musicaData),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.status === "success") {
                    $('#editModal').modal('hide');
                    carregarMusicas();
                }
            },
            error: function(xhr) {
                console.error("Erro ao editar:", xhr.responseText);
                alert('Erro ao editar.');
            }
        });
    });

    $(document).on('click', '.delete-btn', function () {
    var userId = $(this).data('id');
    
    if (confirm("Tem certeza que deseja excluir este usuário?")) {
        $.ajax({
            url: '../../controllers/MusicaController.php',  // Corrigido para o controller correto
            type: 'POST',
            contentType: 'application/json',  // Enviando como JSON
            data: JSON.stringify({ action: 'excluir', id: userId }),  // Enviando a ação 'excluir' corretamente
            success: function(response) {
                console.log(response);  // Verifique a resposta no console
                
                // Aqui não precisamos de JSON.parse, pois a resposta já é um objeto
                if (response.status == 'success') {
                    alert('Música excluída com sucesso!');
                    location.reload();  // ou manipule a exclusão na tabela sem recarregar a página
                } else {
                    alert('Erro ao excluir usuário.');
                }
            },
            error: function(xhr, status, error) {
                alert('Erro na requisição AJAX: ' + error);
            }
        });
    }
});


    carregarMusicas();
});
</script>
</body>
</html>