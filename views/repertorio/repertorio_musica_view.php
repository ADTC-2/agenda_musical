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
    <title>Relacionamento Repertórios e Músicas</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="../../assets/img/logoadtc2__new.png" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        /* Estilo semelhante ao modelo fornecido */
        .card {
            border: 1px solid #ddd;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .card h5 {
            margin: 0 0 10px 0;
            font-size: 1.5em;
            color: #333;
        }

        .card p {
            margin: 5px 0;
            color: #555;
        }

        .card .btn {
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .table-responsive {
                display: none;
            }

            .card {
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
            <i class="fas fa-plus"></i> Adicionar Música ao Repertório
        </button>

        <!-- Campo de busca -->
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar relacionamento...">
        </div>

        <!-- Cards de relacionamentos -->
        <div id="relacionamentosList" class="row">
            <!-- Cards serão carregados aqui via AJAX -->
        </div>
    </main>

    <!-- Modal para adicionar música ao repertório -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Adicionar Música ao Repertório</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createRelacionamentoForm">
                        <div class="mb-3">
                            <label for="createRepertorioId" class="form-label">Repertório</label>
                            <select class="form-control" id="createRepertorioId" required>
                                <!-- Opções serão carregadas via AJAX -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="createMusicaId" class="form-label">Música</label>
                            <select class="form-control" id="createMusicaId" required>
                                <!-- Opções serão carregadas via AJAX -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="createCategoria" class="form-label">Categoria</label>
                            <select class="form-control" id="createCategoria" required>
                                <option value="criança">Criança</option>
                                <option value="adolescente">Adolescente</option>
                                <option value="jovem">Jovem</option>
                                <option value="senhoras">Senhoras</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar relacionamento -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Relacionamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRelacionamentoForm">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editRepertorioId" class="form-label">Repertório</label>
                            <select class="form-control" id="editRepertorioId" required>
                                <!-- Opções serão carregadas via AJAX -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editMusicaId" class="form-label">Música</label>
                            <select class="form-control" id="editMusicaId" required>
                                <!-- Opções serão carregadas via AJAX -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editCategoria" class="form-label">Categoria</label>
                            <select class="form-control" id="editCategoria" required>
                                <option value="criança">Criança</option>
                                <option value="adolescente">Adolescente</option>
                                <option value="jovem">Jovem</option>
                                <option value="senhoras">Senhoras</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para excluir relacionamento -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" style="display: block;" inert>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Excluir Relacionamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza de que deseja excluir este relacionamento?</p>
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
            // Função para carregar relacionamentos
            function carregarRelacionamentos() {
    $.ajax({
        url: '../../controllers/RepertorioMusicaController.php',
        method: 'GET',
        data: { action: 'listar' },
        dataType: 'json',
        success: function(response) {
            console.log(response);  // Verifique o conteúdo completo da resposta

            if (!response || response.status !== "success" || !Array.isArray(response.data)) {
                alert("Erro ao carregar relacionamentos: resposta inválida ou vazia.");
                console.error("Resposta inesperada:", response);
                return;
            }

            // Verifica se existem dados
            if (response.data.length === 0) {
                $('#relacionamentosList').html('<p>Nenhum relacionamento encontrado.</p>');
                return;
            }

            let relacionamentoCards = response.data.map(relacionamento => `
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <div class="row">
                                <div class="col-12">
                                    <h5>${relacionamento.repertorio_nome}</h5>
                                </div>
                                <div class="col-12">
                                    <p><strong>Música:</strong> ${relacionamento.musica_nome}</p>
                                </div>
                                <div class="col-12">
                                    <p><strong>Categoria:</strong> ${relacionamento.categoria}</p>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-info btn-sm w-100 mb-2 edit-btn" data-id="${relacionamento.repertorio_id}" data-musica-id="${relacionamento.musica_id}">Editar</button>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-danger btn-sm w-100 delete-btn" data-id="${relacionamento.repertorio_id}" data-musica-id="${relacionamento.musica_id}">Excluir</button>
                                </div>
                            </div>
                        </div>
                    </div>


            `).join('');
            
            $('#relacionamentosList').html(relacionamentoCards);
        },
        error: function(xhr, status, error) {
            console.error("Erro AJAX:", status, error, xhr.responseText);
            alert('Erro na comunicação com o servidor.');
        }
    });
}



            // Função para carregar repertórios
            function carregarRepertorios(selectId) {
                $.ajax({
                    url: '../../controllers/RepertorioController.php',
                    method: 'GET',
                    data: { action: 'listar' },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success" && Array.isArray(response.data)) {
                            let options = response.data.map(repertorio => `
                                <option value="${repertorio.id}">${repertorio.evento_nome}</option>
                            `).join('');
                            $(selectId).html(options);
                        } else {
                            console.error("Erro ao carregar repertórios:", response);
                        }
                    },
                    error: function(xhr) {
                        console.error("Erro AJAX ao carregar repertórios:", xhr.responseText);
                    }
                });
            }

            // Função para carregar músicas
            function carregarMusicas(selectId) {
                console.log("Iniciando carregamento de músicas...");
                $.ajax({
                    url: '../../controllers/MusicaController.php',
                    method: 'POST', // Alterado para POST
                    data: { action: 'listar' }, // A ação é enviada no corpo da requisição
                    dataType: 'json',
                    success: function(response) {
                        console.log("Resposta do servidor:", response);
                        if (response.status === "success" && Array.isArray(response.data)) {
                            let options = response.data.map(musica => `
                                <option value="${musica.id}">${musica.titulo}</option>
                            `).join('');
                            $(selectId).html(options);
                            console.log("Músicas carregadas com sucesso!");
                        } else {
                            console.error("Erro ao carregar músicas:", response);
                        }
                    },
                    error: function(xhr) {
                        console.error("Erro AJAX ao carregar músicas:", xhr.responseText);
                    }
                });
            }

            // Carregar repertórios e músicas ao abrir o modal de criação
            $('#createModal').on('show.bs.modal', function () {
                carregarRepertorios('#createRepertorioId');
                carregarMusicas('#createMusicaId');
            });

            // Carregar repertórios e músicas ao abrir o modal de edição
            $(document).on('click', '.edit-btn', function () {
                let repertorioId = $(this).data('id');
                let musicaId = $(this).data('musica-id');

                // Carregar repertórios e músicas
                carregarRepertorios('#editRepertorioId');
                carregarMusicas('#editMusicaId');

                // Buscar dados do relacionamento
                $.ajax({
                    url: '../../controllers/RepertorioMusicaController.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ action: 'editar', repertorio_id: repertorioId, musica_id: musicaId }),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);  // Depuração para ver a resposta do servidor
                        if (response.status === "error") {
                            alert(response.message);
                            return;
                        }
                        if (response.status === "success" && response.data) {
                            $('#editId').val(response.data.repertorio_id);
                            $('#editRepertorioId').val(response.data.repertorio_id);
                            $('#editMusicaId').val(response.data.musica_id);
                            $('#editCategoria').val(response.data.categoria);
                            $('#editModal').modal('show');
                        } else {
                            alert("Erro ao carregar os dados do relacionamento.");
                        }
                    },
                    error: function(xhr) {
                        console.error("Erro ao buscar relacionamento:", xhr.responseText);
                        alert('Erro ao buscar dados do relacionamento.');
                    }
                });
            });

            // Submeter formulário de criação
            $('#createRelacionamentoForm').on('submit', function (e) {
                e.preventDefault();
                let relacionamentoData = {
                    action: 'cadastrar',
                    repertorio_id: $('#createRepertorioId').val(),
                    musica_id: $('#createMusicaId').val(),
                    categoria: $('#createCategoria').val()
                };

                $.ajax({
                    url: '../../controllers/RepertorioMusicaController.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(relacionamentoData),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);  // Depuração para ver a resposta do servidor
                        alert(response.message);  // Mensagem do servidor
                        if (response.status === "success") {
                            $('#createModal').modal('hide');
                            $('#createRelacionamentoForm')[0].reset();
                            carregarRelacionamentos();
                        }
                    },
                    error: function(xhr) {
                        console.error("Erro ao cadastrar:", xhr.responseText);
                        alert('Erro ao cadastrar.');
                    }
                });
            });

            // Submeter formulário de edição
            $('#editRelacionamentoForm').on('submit', function (e) {
                e.preventDefault();
                let relacionamentoData = {
                    action: 'atualizar',
                    repertorio_id: $('#editRepertorioId').val(),
                    musica_id: $('#editMusicaId').val(),
                    categoria: $('#editCategoria').val()
                };

                $.ajax({
                    url: '../../controllers/RepertorioMusicaController.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(relacionamentoData),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);  // Depuração para ver a resposta do servidor
                        alert(response.message);  // Mensagem do servidor
                        if (response.status === "success") {
                            $('#editModal').modal('hide');
                            carregarRelacionamentos();
                        }
                    },
                    error: function(xhr) {
                        console.error("Erro ao atualizar:", xhr.responseText);
                        alert('Erro ao atualizar o relacionamento.');
                    }
                });
            });

            // Excluir relacionamento
            $(document).on('click', '.delete-btn', function () {
                let repertorioId = $(this).data('id');
                let musicaId = $(this).data('musica-id');
                if (!confirm("Tem certeza que deseja excluir este relacionamento?")) return;

                $.ajax({
                    url: '../../controllers/RepertorioMusicaController.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ action: 'deletar', repertorio_id: repertorioId, musica_id: musicaId }),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);  // Depuração para ver a resposta do servidor
                        alert(response.message);  // Mensagem do servidor
                        if (response.status === "success") {
                            carregarRelacionamentos();
                        }
                    },
                    error: function(xhr) {
                        console.error("Erro ao excluir:", xhr.responseText);
                        alert('Erro ao excluir o relacionamento.');
                    }
                });
            });

            // Carregar relacionamentos ao carregar a página
            carregarRelacionamentos();
        });
    </script>
</body>
</html>