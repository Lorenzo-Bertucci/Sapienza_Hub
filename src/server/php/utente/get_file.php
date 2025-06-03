<?php
header('Content-Type: application/json');
session_start();

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$user_id = pg_escape_string($conn, $_GET['id']);


$query = "SELECT id,codice,nome,nomefile,dat FROM materiale_didattico m JOIN esami e ON m.esame=e.codice  WHERE id_utente=$1 ORDER BY dat";

$result = pg_query_params($conn, $query,array($user_id));

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero dei dati.']);
    exit;
}

$files = [];
while ($row = pg_fetch_assoc($result)) {
    $files[] = $row;
}

pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'files' => $files]);
?>