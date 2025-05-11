<?php
header('Content-Type: application/json');

// Connessione al database PostgreSQL
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

// Recupera il codice del corso di laurea dall'URL
if (!isset($_GET['codice'])) {
    echo json_encode(['success' => false, 'message' => 'Codice del corso non specificato.']);
    exit;
}

$codice = pg_escape_string($conn, $_GET['codice']);

// Query per recuperare le informazioni dell'esame
$query = "SELECT * FROM esami WHERE codice = $1";
$result = pg_query_params($conn, $query, array($codice));

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