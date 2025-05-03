<?php
header('Content-Type: application/json');

// Connessione al database PostgreSQL
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

// Recupera il codice del corso dall'URL
if (!isset($_GET['esami'])) {
    echo json_encode(['success' => false, 'message' => 'Codice del corso non specificato.']);
    exit;
}

$corso = pg_escape_string($conn, $_GET['esami']);


// Query per recuperare gli esami associati al corso
$query = "SELECT codice,nome,anno,semestre FROM ".$corso;
$result = pg_query($conn, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero degli esami.']);
    exit;
}

// Creazione dell'array degli esami
$esami = [];
while ($row = pg_fetch_assoc($result)) {
    $esami[] = $row;
}

// Liberazione della memoria e chiusura della connessione
pg_free_result($result);
pg_close($conn);

// Restituzione dei dati come JSON
echo json_encode(['success' => true, 'data' => $esami]);
?>