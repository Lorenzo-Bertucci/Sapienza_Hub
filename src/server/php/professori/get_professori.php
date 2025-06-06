<?php
header('Content-Type: application/json');

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$query = "SELECT id,nome FROM professori ORDER BY nome";   
$result = pg_query($conn, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero dei dati.']);
    exit;
}

$professori = [];
while ($row = pg_fetch_assoc($result)) {
    $professori[] = $row;
}

pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'professori' => $professori]);
?>