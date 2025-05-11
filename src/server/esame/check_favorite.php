<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Devi effettuare il login.']);
    exit;
}

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$corso = pg_escape_string($conn, $_GET['corso']);
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM preferiti_esami WHERE id_utente = $1 AND codice = $2";
$result = pg_query_params($conn, $query, array($user_id, $corso));

$isFavorite = pg_num_rows($result) > 0;

pg_close($conn);

echo json_encode(['success' => true, 'isFavorite' => $isFavorite]);
?>