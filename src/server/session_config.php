<?php
// Configura la durata della sessione
ini_set('session.gc_maxlifetime', 1200); // 1200 secondi = 20 minuti
ini_set('session.cookie_lifetime', 0); // La sessione termina quando il browser viene chiuso

session_start(); // Inizia la sessione

?>