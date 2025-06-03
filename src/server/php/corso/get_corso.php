<?php
header('Content-Type: application/json');

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$corso = pg_escape_string($conn, $_GET['corso']);

$query = "SELECT * FROM corsi WHERE codice = $1";
$result = pg_query_params($conn, $query, array($corso));

if (!$result || pg_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Corso di laurea non trovato.']);
    exit;
}

$corso = pg_fetch_assoc($result);

pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'corso' => $corso]);
?>