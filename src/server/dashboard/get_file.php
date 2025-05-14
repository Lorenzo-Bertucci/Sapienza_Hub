<?php
header('Content-Type: application/json');
session_start();

// Connessione al database PostgreSQL
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

// Recupero dell'ID utente dalla sessione
$user_id = $_SESSION['user_id'];

$query = "SELECT id,codice,nome,nomefile,dat FROM materiale_didattico m JOIN esami e ON m.esame=e.codice  WHERE id_utente=$1 ORDER BY dat";

// Query per recuperare i corsi di laurea
$result = pg_query_params($conn, $query,array($user_id));

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero dei dati.']);
    exit;
}

// Creazione dell'array dei corsi
$files = [];
while ($row = pg_fetch_assoc($result)) {
    $files[] = $row;
}

// Liberazione della memoria e chiusura della connessione
pg_free_result($result);
pg_close($conn);

// Restituzione dei dati come JSON
echo json_encode(['success' => true, 'files' => $files]);
?>