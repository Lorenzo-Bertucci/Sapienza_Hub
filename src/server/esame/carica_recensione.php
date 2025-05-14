<?php
header('Content-Type: application/json');
session_start();

$conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user_id'];
    $testo = pg_escape_string($conn, $_POST['testo']);
    $esame=pg_escape_string($conn, $_POST['esame']);

    $insert_query = "INSERT INTO recensioni_esami (id_utente, testo, codice) VALUES ($1, $2, $3)";
    $insert_result = pg_query_params($conn, $insert_query, array($id, $testo, $esame));
    if (!$insert_result) {
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'inserimento della recensione: ' . pg_last_error($conn)]);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Recensione aggiunta con successo!']);
    exit;
}

pg_free_result($insert_result);
pg_close($conn);
?>