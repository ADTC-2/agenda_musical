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
    <title>Usuários</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="../../assets/img/logoadtc2__new.png" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        .welcome-title {
            font-size: 2.5em;
            font-weight: bold;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .welcome-message {
            font-size: 1.2em;
            text-align: center;
            color: #555;
            font-style: italic;
        }

        .user-card {
            border: 1px solid #ddd;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .user-card h5 {
            margin: 0 0 10px 0;
            font-size: 1.5em;
            color: #333;
        }

        .user-card p {
            margin: 5px 0;
            color: #555;
        }

        .user-card .btn {
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .table-responsive {
                display: none;
            }

            .user-card {
                flex-direction: row;
                justify-content: space-between;
            }
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
                                <a class="nav-link active" href="../views/dashboard.php">
                                    <i class="fas fa-home"></i> Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../controllers/AuthController.php?action=logout">
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
            <i class="fas fa-plus"></i> Usuário
        </button>

        <!-- Cards de usuários -->
        <div id="usuariosList" class="row">
            <!-- Cards serão carregados aqui via AJAX -->
        </div>
    </main>

    <!-- Modal para cadastrar usuário -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Cadastrar Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createUserForm">
                        <div class="mb-3">
                            <label for="createNome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="createNome" required>
                        </div>
                        <div class="mb-3">
                            <label for="createEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="createEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="createSenha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="createSenha" required>
                        </div>
                        <div class="mb-3">
                            <label for="createTipo" class="form-label">Tipo</label>
                            <select class="form-select" id="createTipo" required>
                                <option value="admin">Admin</option>
                                <option value="musico">Músico</option>
                                <option value="regente">Regente</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar usuário -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editNome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="editNome" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTipo" class="form-label">Tipo</label>
                            <select class="form-select" id="editTipo" required>
                                <option value="admin">Admin</option>
                                <option value="musico">Músico</option>
                                <option value="regente">Regente</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para excluir usuário -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Excluir Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza de que deseja excluir este usuário?</p>
                    <button type="button" id="deleteConfirm" class="btn btn-danger w-100">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Carregar usuários e exibir em formato de cartão
            function carregarUsuarios() {
                $.ajax({
                    url: '../../controllers/UsuarioController.php?action=listar',
                    success: function (response) {
                        const data = JSON.parse(response);
                        let userCards = '';
                        data.forEach(function (user) {
                            userCards += `
                                <div class="col-md-4 col-lg-3 mb-4">
                                    <div class="user-card">
                                        <h5>${user.nome}</h5>
                                        <p><strong>Email:</strong> ${user.email}</p>
                                        <p><strong>Tipo:</strong> ${user.tipo}</p>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-info btn-sm flex-grow-1" onclick="editUser(${user.id})">Editar</button>
                                            <button class="btn btn-danger btn-sm flex-grow-1" onclick="deleteUser(${user.id})">Excluir</button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        $('#usuariosList').html(userCards);
                    }
                });
            }

            // Carregar usuários ao abrir a página
            carregarUsuarios();

            // Cadastrar usuário
            $('#createUserForm').on('submit', function (e) {
                e.preventDefault();
                const nome = $('#createNome').val();
                const email = $('#createEmail').val();
                const senha = $('#createSenha').val();
                const tipo = $('#createTipo').val();

                $.ajax({
                    url: '../../controllers/UsuarioController.php?action=cadastrar',
                    method: 'POST',
                    data: {
                        action: 'cadastrar',
                        nome: nome,
                        email: email,
                        senha: senha,
                        tipo: tipo
                    },
                    success: function (response) {
                        const data = JSON.parse(response);
                        if (data.status === "success") {
                            alert(data.message);
                            $('#createModal').modal('hide');
                            carregarUsuarios();
                        } else {
                            alert(data.message);
                        }
                    }
                });
            });

            // Editar usuário
            window.editUser = function (id) {
                $.ajax({
                    url: `../../controllers/UsuarioController.php?action=editar&id=${id}`,
                    success: function (response) {
                        const data = JSON.parse(response);
                        $('#editId').val(data.id);
                        $('#editNome').val(data.nome);
                        $('#editEmail').val(data.email);
                        $('#editTipo').val(data.tipo);
                        $('#editModal').modal('show');
                    }
                });
            };

            // Atualizar usuário
            $('#editUserForm').on('submit', function (e) {
                e.preventDefault();
                const id = $('#editId').val();
                const nome = $('#editNome').val();
                const email = $('#editEmail').val();
                const tipo = $('#editTipo').val();

                $.ajax({
                    url: '../../controllers/UsuarioController.php?action=update',
                    method: 'POST',
                    data: {
                        action: 'update',
                        id: id,
                        nome: nome,
                        email: email,
                        tipo: tipo
                    },
                    success: function (response) {
                        const data = JSON.parse(response);
                        if (data.status === "success") {
                            alert(data.message);
                            $('#editModal').modal('hide');
                            carregarUsuarios();
                        } else {
                            alert(data.message);
                        }
                    }
                });
            });

            // Excluir usuário
            window.deleteUser = function (id) {
                $('#deleteConfirm').off('click').on('click', function () {
                    $.ajax({
                        url: `../../controllers/UsuarioController.php?action=delete&id=${id}`,
                        success: function (response) {
                            const data = JSON.parse(response);
                            if (data.status === "success") {
                                alert(data.message);
                                $('#deleteModal').modal('hide');
                                carregarUsuarios();
                            } else {
                                alert(data.message);
                            }
                        }
                    });
                });
                $('#deleteModal').modal('show');
            };
        });
    </script>
</body>

</html>