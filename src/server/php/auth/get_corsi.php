<?php

header('Content-Type: application/json');

// Connessione al database PostgreSQL
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database']);
    exit;
}

// Recupera l’elenco dei corsi di studi
$query = "SELECT codice, nome FROM corsi ORDER BY nome ASC";
$result = pg_query($conn, $query);
if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero dei corsi']);
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