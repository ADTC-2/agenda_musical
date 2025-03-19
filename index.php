<?php
// Impedir o acesso direto a este arquivo
if (basename($_SERVER['PHP_SELF']) === 'index.php') {
    // Redireciona para o public/index.php
    header("Location: public/index.php");
    exit;
} else {
    // Impede o acesso direto a outros arquivos PHP
    die('Acesso negado.');
}
?>