<?php
header('Content-Type: application/json');
session_start();

// Connessione al database PostgreSQL
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}
// Avvio della sessione


// Controllo se l'ID utente è presente nella sessione

// Recupero dell'ID utente dalla sessione
$user_id = $_SESSION['user_id'];
// Recupero dell'ID della recensione dall'URL
$file_id = $_GET['id'];

$query = "DELETE FROM materiale_didattico  WHERE id_utente=$1 and id=$2 ";


$result = pg_query_params($conn, $query,array($user_id,$file_id));

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero dei dati.']);
    exit;
}

// Creazione dell'array dei corsi


// Liberazione della memoria e chiusura della connessione
pg_free_result($result);
pg_close($conn);

// Restituzione dei dati come JSON
echo json_encode(['success' => true, 'message' => 'Recensione eliminata con successo.']);
?>