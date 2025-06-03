<?php
header('Content-Type: application/json');

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$codice = pg_escape_string($conn, $_GET['codice']);

$query = "SELECT * FROM esami WHERE codice = $1";
$result = pg_query_params($conn, $query, array($codice));

if (!$result || pg_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Esame non trovato.']);
    exit;
}

$esame = pg_fetch_assoc($result);

pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'esame' => $esame]);
?>