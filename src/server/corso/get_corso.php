<?php
header('Content-Type: application/json');

// Connessione al database PostgreSQL
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

// Recupera il codice del corso di laurea dall'URL
if (!isset($_GET['corso'])) {
    echo json_encode(['success' => false, 'message' => 'Codice del corso non specificato.']);
    exit;
}

// Sanitizza il codice del corso per prevenire SQL injection
$corso = pg_escape_string($conn, $_GET['corso']);

// Query per recuperare le informazioni del corso di laurea
$query = "SELECT * FROM corsi WHERE codice = $1";
$result = pg_query_params($conn, $query, array($corso));

if (!$result || pg_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Corso di laurea non trovato.']);
    exit;
}

// Recupera i dati del corso
$corso = pg_fetch_assoc($result);

// Chiudi la connessione al database
pg_free_result($result);
pg_close($conn);

// Restituisci i dati come JSON
echo json_encode(['success' => true, 'corso' => $corso]);
?>