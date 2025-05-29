<?php
header('Content-Type: application/json');

$conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utente=pg_escape_string($conn, $_POST['utente']);

    $check_query = "SELECT * FROM gestione WHERE utente = $1";
    $check_result = pg_query_params($conn, $check_query, array($utente));

    if (pg_num_rows($check_result) == 0) {
        echo json_encode(['success' => false, 'message' => 'L\'Utente non esiste!']);
        pg_free_result($check_result);
        pg_close($conn);
        exit;
    }else{

        $query = "DELETE FROM gestione WHERE utente = $1";
        $result = pg_query_params($conn, $query, array($utente));
        if (!$result) {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'eliminazione dell\'utente: ' . pg_last_error($conn)]);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Utente Privilegiato eliminato con successo!']);
        pg_free_result($result);
        pg_close($conn);
        exit;
    }

}
?>