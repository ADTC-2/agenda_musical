<?php
// Carregar configurações do .env
$env = parse_ini_file(__DIR__ . '/../.env');

// Configurações globais
define('APP_ENV', $env['APP_ENV']);
define('APP_DEBUG', filter_var($env['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN));
define('APP_URL', $env['APP_URL']);

// Configurações de sessão
session_start();
?>
