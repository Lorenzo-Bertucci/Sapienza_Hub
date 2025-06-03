<?php
header('Content-Type: application/json');

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$esame = pg_escape_string($conn, $_GET['esame']);

$query = "SELECT * FROM insegna where codice_esame='$esame'";
$result = pg_query($conn, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero dei professori.']);
    exit;
}

$prof = [];
while ($row = pg_fetch_assoc($result)) {
    $prof[] = $row;
}

pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'professori' => $prof]);
?>