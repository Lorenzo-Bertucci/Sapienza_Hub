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

// Query per recuperare i corsi di laurea
$query = "SELECT codice, nome FROM preferiti_esami WHERE id_utente=$1 ORDER BY nome";
$result = pg_query_params($conn, $query,array($user_id));

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero dei dati.']);
    exit;
}

// Creazione dell'array dei corsi
$esami = [];
while ($row = pg_fetch_assoc($result)) {
    $esami[] = $row;
}

// Liberazione della memoria e chiusura della connessione
pg_free_result($result);
pg_close($conn);

// Restituzione dei dati come JSON
echo json_encode(['success' => true, 'esami' => $esami]);
?>