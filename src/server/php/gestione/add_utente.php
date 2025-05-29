<?php
header('Content-Type: application/json');

$conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utente= pg_escape_string($conn, $_POST['utente']);
    $password=password_hash($_POST['password'], PASSWORD_DEFAULT);
    

    $query = "SELECT * FROM gestione WHERE utente=$1";
    $check_result = pg_query_params($conn, $query, array($utente));

if (pg_num_rows($check_result) > 0) {
    echo json_encode(['success' => false, 'message' => 'Utente già registrata.']);
    exit;
} else {
    $query = "INSERT INTO gestione (utente, password) VALUES ($1, $2)";
    $result = pg_query_params($conn, $query, array($utente, $password));

    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Errore durante l\'inserimento dell\'utente.']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Inserimento avvenuto con successo.']);
    exit;
}

}
?>