<!-- filepath: /Users/lorenzo/Documents/Sapienza_Hub/src/server/logout.php -->
<?php
session_start(); // Avvia la sessione

// Rimuove tutte le variabili di sessione
session_unset();

// Distrugge la sessione
session_destroy();

// Reindirizza l'utente alla pagina di login
header('Location: /src/client/html/index.html');
exit;
?>