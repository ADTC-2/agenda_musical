<?php
require_once '../config/database.php';

class RepertorioMusicaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar todas as associações entre repertórios e músicas
    public function listar() {
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    rm.repertorio_id, 
                    rm.musica_id, 
                    rm.categoria, 
                    e.nome AS repertorio_nome, 
                    m.titulo AS musica_nome
                FROM 
                    repertorio_musica rm
                JOIN 
                    repertorios r ON rm.repertorio_id = r.id
                JOIN 
                    musicas m ON rm.musica_id = m.id
                JOIN
                    eventos e ON r.evento_id = e.id
            ");
            
            if ($stmt === false) {
                throw new Exception("Erro ao executar a consulta.");
            }
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    
    // Buscar uma associação específica pelo repertorio_id e musica_id
    public function buscarPorIds($repertorio_id, $musica_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM repertorio_musica WHERE repertorio_id = :repertorio_id AND musica_id = :musica_id");
        $stmt->bindParam(':repertorio_id', $repertorio_id, PDO::PARAM_INT);
        $stmt->bindParam(':musica_id', $musica_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cadastrar uma nova associação entre repertório e música
    public function cadastrar($repertorio_id, $musica_id, $categoria) {
        $stmt = $this->pdo->prepare("INSERT INTO repertorio_musica (repertorio_id, musica_id, categoria) VALUES (:repertorio_id, :musica_id, :categoria)");
        $stmt->bindParam(':repertorio_id', $repertorio_id, PDO::PARAM_INT);
        $stmt->bindParam(':musica_id', $musica_id, PDO::PARAM_INT);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Editar uma associação existente
    public function editar($repertorio_id, $musica_id, $categoria) {
        $stmt = $this->pdo->prepare("UPDATE repertorio_musica SET categoria = :categoria WHERE repertorio_id = :repertorio_id AND musica_id = :musica_id");
        $stmt->bindParam(':repertorio_id', $repertorio_id, PDO::PARAM_INT);
        $stmt->bindParam(':musica_id', $musica_id, PDO::PARAM_INT);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Deletar uma associação
    public function deletar($repertorio_id, $musica_id) {
        $stmt = $this->pdo->prepare("DELETE FROM repertorio_musica WHERE repertorio_id = :repertorio_id AND musica_id = :musica_id");
        $stmt->bindParam(':repertorio_id', $repertorio_id, PDO::PARAM_INT);
        $stmt->bindParam(':musica_id', $musica_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function buscarPorId($repertorioId, $musicaId) {
        $sql = "SELECT * FROM repertorio_musica WHERE repertorio_id = :repertorio_id AND musica_id = :musica_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':repertorio_id', $repertorioId, PDO::PARAM_INT);
        $stmt->bindParam(':musica_id', $musicaId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>