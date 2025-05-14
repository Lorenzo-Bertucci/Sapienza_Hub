<?php
session_start(); // Avvia la sessione

// Rimuove tutte le variabili di sessione
session_unset();

// Distrugge la sessione
session_destroy();

// Recupera l'URL della pagina corrente
$current_page = $_SERVER['HTTP_REFERER'] ?? '/src/client/html/index.html';

// Reindirizza l'utente alla pagina corrente
header("Location: $current_page");
exit;
?>