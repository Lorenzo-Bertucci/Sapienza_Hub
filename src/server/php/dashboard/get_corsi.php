<?php
header('Content-Type: application/json');
session_start();

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT codice, nome FROM preferiti_corsi WHERE id_utente=$1 ORDER BY nome";
$result = pg_query_params($conn, $query,array($user_id));

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero dei dati.']);
    exit;
}

$corsi = [];
while ($row = pg_fetch_assoc($result)) {
    $corsi[] = $row;
}

pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'corsi' => $corsi]);
?>