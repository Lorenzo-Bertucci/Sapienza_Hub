<?php
header('Content-Type: application/json');

// Connessione al database PostgreSQL
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

// Recupera il codice del corso di laurea dall'URL
if (!isset($_GET['codice']) || !isset($_GET['corso'])) {
    echo json_encode(['success' => false, 'message' => 'Codice del corso non specificato.']);
    exit;
}

$corso = pg_escape_string($conn, $_GET['corso']);
$codice = pg_escape_string($conn, $_GET['codice']);

// Query per recuperare le informazioni dell'esame
$query = "SELECT * FROM esami_".$corso ." WHERE codice = '$codice'";
$result = pg_query($conn, $query);

if (!$result || pg_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Esame non trovato.']);
    exit;
}

// Recupera i dati dell'esame
$esame = pg_fetch_assoc($result);

// Chiudi la connessione al database
pg_free_result($result);
pg_close($conn);

// Restituisci i dati come JSON
echo json_encode(['success' => true, 'data' => $esame]);
?>