<?php
header('Content-Type: application/json');

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$corso = pg_escape_string($conn, $_GET['corso']);

$query = "SELECT codice,nome,anno,semestre FROM esami WHERE corso = $1";
$result = pg_query_params($conn, $query, array($corso));

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero degli esami.']);
    exit;
}

$esami = [];
while ($row = pg_fetch_assoc($result)) {
    $esami[] = $row;
}

pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'esami' => $esami]);
?>