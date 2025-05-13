<?php
    session_start();
    header('Content-Type: application/json');

    $conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'];
        $testo = pg_escape_string($conn, $_POST['testo']);
        $prof_id = pg_escape_string($conn, $_POST['prof_id']);

        $insert_query = "INSERT INTO recensioni_professori (id_utente, testo, id_professore) VALUES ('$user_id', '$testo', '$prof_id')";
        $insert_result = pg_query($conn, $insert_query);

        if ($insert_result) {
            echo json_encode(['success' => true, 'message' => 'Recensione aggiunta con successo!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'inserimento della recensione.' . pg_last_error($conn)]);
        }
        exit;
    }
?>