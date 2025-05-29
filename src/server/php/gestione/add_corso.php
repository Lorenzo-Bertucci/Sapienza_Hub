<?php
header('Content-Type: application/json');

$conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codice=pg_escape_string($conn, $_POST['codice']);
    $nome=pg_escape_string($conn, $_POST['nome']);
    $tipo=pg_escape_string($conn, $_POST['tipo']);
    $laurea=pg_escape_string($conn, $_POST['laurea']);
    $lingua=pg_escape_string($conn, $_POST['lingua']);
    $link=pg_escape_string($conn, $_POST['link']);
    $desc=pg_escape_string($conn, $_POST['desc']);
    $durata=pg_escape_string($conn, $_POST['durata']);

    $check_query = "SELECT * FROM corsi WHERE codice = $1";
    $check_result = pg_query_params($conn, $check_query, array($codice));

    if (pg_num_rows($check_result) > 0) {
        $update_query = "UPDATE corsi SET nome=$2, tipo=$3, laurea=$4, lingua=$5, link=$6, durata=$7, descrizione=$8 WHERE codice=$1";
        $update_result = pg_query_params($conn, $update_query, array($codice, $nome, $tipo, $laurea, $lingua, $link, $durata, $desc));
        if (!$update_result) {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento del corso: ' . pg_last_error($conn)]);
            exit;
        }
        echo json_encode(['success' => true, 'message' => 'Corso aggiornato con successo!']);
        pg_free_result($check_result);
        pg_free_result($update_result);
        pg_close($conn);
        exit;
    }else{

        $insert_query = "INSERT INTO corsi (codice, nome, tipo, laurea, lingua, link, durata, descrizione) VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";
        $insert_result = pg_query_params($conn, $insert_query, array($codice, $nome, $tipo, $laurea, $lingua, $link, $durata, $desc));
        if (!$insert_result) {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'inserimento del corso: ' . pg_last_error($conn)]);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Corso inserito con successo!']);
        pg_free_result($insert_result);
        pg_close($conn);
        exit;
    }

}
?>