<?php
header('Content-Type: application/json');

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome= pg_escape_string($conn, $_POST['nome']);
    $email= pg_escape_string($conn, $_POST['email']);
    $dip= pg_escape_string($conn, $_POST['dip']);
    $ssd= pg_escape_string($conn, $_POST['ssd']);
    $link= pg_escape_string($conn, $_POST['link']);

    $check_query = "SELECT * FROM professori WHERE email = $1";
    $check_result = pg_query_params($conn, $check_query, array($email));

    if (pg_num_rows($check_result) > 0) {
        $update_query = "UPDATE professori SET nome=$1, dip=$2, ssd=$3, link=$4 WHERE email=$5";
        $update_result = pg_query_params($conn, $update_query, array($nome, $dip, $ssd, $link, $email));
        if (!$update_result) {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento del professore']);
            exit;
        }
        echo json_encode(['success' => true, 'message' => 'Professore aggiornato con successo!']);
        pg_free_result($check_result);
        pg_free_result($update_result);
        pg_close($conn);
        exit;
    } else {
        $insert_query = "INSERT INTO professori (nome, email, dip, ssd, link) VALUES ($1, $2, $3, $4, $5)";
        $insert_result = pg_query_params($conn, $insert_query, array($nome, $email, $dip, $ssd, $link));
        if (!$insert_result) {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'inserimento del professore']);
            exit;
        }
        echo json_encode(['success' => true, 'message' => 'Professore inserito con successo!']);
        pg_free_result($insert_result);
        pg_close($conn);
        exit;
    }
}
?>