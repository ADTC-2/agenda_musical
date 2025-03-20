<?php
require_once '../config/database.php';

class Musica
{
    private $pdo;

    // Propriedades
    private $id;
    private $titulo;
    private $cantor_banda;
    private $tom;
    private $bpm;
    private $link;
    private $arquivo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function setCantorBanda($cantor_banda) { $this->cantor_banda = $cantor_banda; }
    public function setTom($tom) { $this->tom = $tom; }
    public function setBpm($bpm) { $this->bpm = $bpm; }
    public function setLink($link) { $this->link = $link; }
    public function setArquivo($arquivo) { $this->arquivo = $arquivo; }

    // Métodos CRUD

    public function listar()
    {
        $stmt = $this->pdo->query("SELECT * FROM musicas ORDER BY titulo");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM musicas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrar($titulo, $cantor_banda, $tom, $bpm, $link, $arquivo)
    {
        $this->setTitulo($titulo);
        $this->setCantorBanda($cantor_banda);
        $this->setTom($tom);
        $this->setBpm($bpm);
        $this->setLink($link);
        $this->setArquivo($arquivo);

        $stmt = $this->pdo->prepare("INSERT INTO musicas (titulo, cantor_banda, tom, bpm, link, arquivo) 
                                     VALUES (:titulo, :cantor_banda, :tom, :bpm, :link, :arquivo)");

        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':cantor_banda', $this->cantor_banda);
        $stmt->bindParam(':tom', $this->tom);
        $stmt->bindParam(':bpm', $this->bpm);
        $stmt->bindParam(':link', $this->link);
        $stmt->bindParam(':arquivo', $this->arquivo);

        return $stmt->execute();
    }

    public function editar($id, $titulo, $cantor_banda, $tom, $bpm, $link, $arquivo)
    {
        $this->setId($id);
        $this->setTitulo($titulo);
        $this->setCantorBanda($cantor_banda);
        $this->setTom($tom);
        $this->setBpm($bpm);
        $this->setLink($link);
        $this->setArquivo($arquivo);
    
        $stmt = $this->pdo->prepare("UPDATE musicas 
                                     SET titulo = :titulo, cantor_banda = :cantor_banda, tom = :tom, bpm = :bpm, link = :link, arquivo = :arquivo 
                                     WHERE id = :id");
    
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':cantor_banda', $this->cantor_banda);
        $stmt->bindParam(':tom', $this->tom);
        $stmt->bindParam(':bpm', $this->bpm);
        $stmt->bindParam(':link', $this->link);
        $stmt->bindParam(':arquivo', $this->arquivo);
    
        return $stmt->execute();
    }

    public function excluir($id)
    {
        $this->setId($id);
        
        // Mudando para a tabela de usuários
        $stmt = $this->pdo->prepare("DELETE FROM musicas WHERE id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    
        return $stmt->execute();  // Retorna o sucesso ou falha da execução
    }
    
}
?>