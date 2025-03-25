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

// Se não tiver evento_id, redireciona para a lista de eventos
if ($evento_id === 0) {
    header('Location: ../eventos/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repertórios do Evento</title>
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
    
    .back-button {
        margin-right: 10px;
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
        
        <!-- Botão de voltar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="../eventos/index.php" class="btn btn-secondary back-button">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <div class="w-100 w-md-auto">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar músicas...">
            </div>
        </div>
        
        <!-- Título do evento -->
        <div class="alert alert-info mb-4">
            <h4 class="alert-heading text-center" id="eventoNome">Carregando informações do evento...</h4>
        </div>
        
        <div id="repertoriosList" class="row">
            <!-- Cards serão carregados aqui via AJAX -->
        </div>
    </main>

    <!-- Modal para associar músicas -->
    <div class="modal fade" id="associarMusicaModal" tabindex="-1" aria-labelledby="associarMusicaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="associarMusicaModalLabel">Associar Músicas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="musicasDisponiveis">
                        <!-- Lista de músicas disponíveis será carregada aqui -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" id="salvarAssociacoes" class="btn btn-primary">Salvar</button>
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
        // Variável global para armazenar o ID do evento
        const eventoId = <?php echo $evento_id; ?>;
        
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
        
        // Carregar informações do evento
        function carregarInfoEvento() {
            $.ajax({
                url: '../../controllers/EventoController.php',
                method: 'POST',
                data: {
                    action: 'buscarPorId',
                    id: eventoId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        const evento = response.data;
                        $('#eventoNome').html(`
                            ${evento.nome} 
                            <small class="text-muted">(${formatarData(evento.data_hora)})</small>
                        `);
                    } else {
                        $('#eventoNome').text('Evento não encontrado');
                    }
                },
                error: function(xhr, status, error) {
                    $('#eventoNome').text('Erro ao carregar evento');
                    console.error('Erro ao carregar evento:', error);
                }
            });
        }
        
        // Carregar repertório específico do evento
        function carregarRepertorioEvento() {
            $.ajax({
                url: '../../controllers/RepertorioController.php',
                method: 'POST',
                data: { 
                    action: 'buscarPorEvento',
                    evento_id: eventoId,
                    with_musicas: true
                },
                dataType: 'json',
                success: function(response) {
                    $('#repertoriosList').empty();

                    if (response.status === "success") {
                        if (response.data && response.data.length > 0) {
                            // Como estamos buscando por evento, deve retornar apenas um repertório
                            const repertorio = response.data[0];
                            
                            let musicasHTML = '';
                            
                            if (repertorio.musicas && repertorio.musicas.length > 0) {
                                musicasHTML = `
                                <div class="mt-3">
                                    <h6 class="mb-2 text-primary d-flex justify-content-between align-items-center">
                                        <span>Músicas do Repertório</span>
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
                                    <small class="text-muted">Nenhuma música associada a este repertório</small>
                                </div>`;
                            }

                            const isMobile = window.innerWidth <= 768;
                            
                            $('#repertoriosList').append(`
                                <div class="col-12">
                                    <div class="repertorio-card">
                                        <div>
                                            <h5>Repertório para: ${repertorio.evento_nome || 'Evento sem nome'}</h5>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>${formatarData(repertorio.evento_data)}</small>
                                                <span class="badge bg-secondary">ID: ${repertorio.id || ''}</span>
                                            </div>
                                            ${musicasHTML}
                                        </div>
                                        <div class="buttons-container mt-3">
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <button class="btn btn-primary btn-sm w-100" id="associarMusicasBtn" data-repertorio-id="${repertorio.id}">
                                                        <i class="fas fa-link"></i> Associar Músicas
                                                    </button>
                                                </div>
                                                <div class="col-md-4">
                                                    <a href="../musicas/index.php" class="btn btn-success btn-sm w-100">
                                                        <i class="fas fa-plus"></i> Nova Música
                                                    </a>
                                                </div>
                                                <div class="col-md-4">
                                                    <a href="../musicas/index.php" class="btn btn-outline-secondary btn-sm w-100">
                                                        <i class="fas fa-search"></i> Buscar Músicas
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        } else {
                            // Se não existir repertório para este evento, oferece opção para criar
                            $('#repertoriosList').html(`
                                <div class="col-12">
                                    <div class="alert alert-warning text-center py-4">
                                        <h4>Nenhum repertório criado para este evento</h4>
                                        <p class="mt-3">
                                            <button class="btn btn-primary" id="criarRepertorioBtn">
                                                <i class="fas fa-plus"></i> Criar Repertório para Este Evento
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            `);
                        }
                    } else {
                        console.error('Erro ao carregar repertório:', response.message);
                        $('#repertoriosList').html('<div class="col-12"><div class="alert alert-danger text-center py-4">Erro ao carregar repertório.</div></div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', error);
                    $('#repertoriosList').html('<div class="col-12"><div class="alert alert-danger text-center py-4">Erro na conexão com o servidor.</div></div>');
                }
            });
        }
        
        // Carregar músicas disponíveis para associação
        function carregarMusicasDisponiveis(repertorioId) {
            $.ajax({
                url: '../../controllers/MusicaController.php',
                method: 'POST',
                data: {
                    action: 'listar'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success" && Array.isArray(response.data)) {
                        let musicasHTML = '<div class="list-group">';
                        
                        // Primeiro, precisamos saber quais músicas já estão associadas
                        $.ajax({
                            url: '../../controllers/RepertorioController.php',
                            method: 'POST',
                            data: {
                                action: 'listarMusicasAssociadas',
                                repertorio_id: repertorioId
                            },
                            dataType: 'json',
                            success: function(associacaoResponse) {
                                const musicasAssociadas = associacaoResponse.status === "success" ? 
                                    associacaoResponse.data.map(m => m.id) : [];
                                
                                response.data.forEach(function(musica) {
                                    const isAssociada = musicasAssociadas.includes(musica.id);
                                    
                                    musicasHTML += `
                                        <label class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <input type="checkbox" class="form-check-input me-2 musica-checkbox" 
                                                    value="${musica.id}" ${isAssociada ? 'checked' : ''}>
                                                ${musica.titulo || 'Música sem título'}
                                                ${musica.categoria ? `<span class="badge bg-light text-dark ms-2">${musica.categoria}</span>` : ''}
                                            </div>
                                            <a href="../musicas/detalhes.php?id=${musica.id}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                        </label>`;
                                });
                                
                                musicasHTML += '</div>';
                                $('#musicasDisponiveis').html(musicasHTML);
                                $('#associarMusicaModal').modal('show');
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Erro', 'Erro ao verificar músicas associadas', 'error');
                            }
                        });
                    } else {
                        Swal.fire('Erro', 'Erro ao carregar músicas disponíveis', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Erro', 'Erro ao carregar músicas: ' + error, 'error');
                }
            });
        }
        
        // Criar repertório para o evento
        $(document).on('click', '#criarRepertorioBtn', function() {
            Swal.fire({
                title: 'Criar Repertório',
                text: "Deseja criar um repertório para este evento?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, criar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../controllers/RepertorioController.php',
                        method: 'POST',
                        data: {
                            action: 'cadastrar',
                            evento_id: eventoId
                        },
                        dataType: 'json',
                        success: function(response) {
                            Swal.fire(
                                response.status === "success" ? 'Sucesso!' : 'Erro',
                                response.message,
                                response.status === "success" ? 'success' : 'error'
                            );
                            if (response.status === "success") {
                                carregarRepertorioEvento();
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Erro', 'Erro ao criar repertório: ' + error, 'error');
                        }
                    });
                }
            });
        });
        
        // Abrir modal para associar músicas
        $(document).on('click', '#associarMusicasBtn', function() {
            const repertorioId = $(this).data('repertorio-id');
            carregarMusicasDisponiveis(repertorioId);
            $('#salvarAssociacoes').data('repertorio-id', repertorioId);
        });
        
        // Salvar associações de músicas
        $('#salvarAssociacoes').on('click', function() {
            const repertorioId = $(this).data('repertorio-id');
            const musicasSelecionadas = [];
            
            $('.musica-checkbox:checked').each(function() {
                musicasSelecionadas.push($(this).val());
            });
            
            $.ajax({
                url: '../../controllers/RepertorioController.php',
                method: 'POST',
                data: {
                    action: 'associarMusicas',
                    repertorio_id: repertorioId,
                    musicas: musicasSelecionadas
                },
                dataType: 'json',
                success: function(response) {
                    Swal.fire(
                        response.status === "success" ? 'Sucesso!' : 'Erro',
                        response.message,
                        response.status === "success" ? 'success' : 'error'
                    );
                    if (response.status === "success") {
                        $('#associarMusicaModal').modal('hide');
                        carregarRepertorioEvento();
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Erro', 'Erro ao salvar associações: ' + error, 'error');
                }
            });
        });
        
        // Filtro de busca nas músicas
        $('#searchInput').on('keyup', function() {
            const searchText = $(this).val().toLowerCase();
            $('.musica-item').each(function() {
                const musicaText = $(this).text().toLowerCase();
                $(this).toggle(musicaText.includes(searchText));
            });
        });
        
        // Redimensionar tela - ajustar layout
        $(window).resize(function() {
            // Atualiza o layout se necessário
        });
        
        // Carregar dados ao abrir a página
        carregarInfoEvento();
        carregarRepertorioEvento();
    });
    </script>
</body>

</html>