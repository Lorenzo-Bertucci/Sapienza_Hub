<?php
header('Content-Type: application/json');
session_start();

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$file_id = pg_escape_string($conn,$_GET['id']);

$query = "DELETE FROM materiale_didattico WHERE id_utente=$1 and id=$2 ";

$result = pg_query_params($conn, $query,array($user_id,$file_id));

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero dei dati.']);
    exit;
}

pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'message' => 'Recensione eliminata con successo.']);
?>