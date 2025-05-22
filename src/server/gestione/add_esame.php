<?php
header('Content-Type: application/json');

$conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codice= pg_escape_string($conn, $_POST['codice']);
    $nome= pg_escape_string($conn, $_POST['nome']);
    $anno= pg_escape_string($conn, $_POST['anno']);
    $semestre= pg_escape_string($conn, $_POST['semestre']);
    $cfu= pg_escape_string($conn, $_POST['cfu']);
    $ssd= pg_escape_string($conn, $_POST['ssd']);
    $lingua= pg_escape_string($conn, $_POST['lingua']);
    $tipo= pg_escape_string($conn, $_POST['tipo']);
    $ambito= pg_escape_string($conn, $_POST['ambito']);
    $link= pg_escape_string($conn, $_POST['link']);
    $desc= pg_escape_string($conn, $_POST['desc']);
    $corso= pg_escape_string($conn, $_POST['corso']);

    $check_query = "SELECT * FROM esami WHERE codice = $1";
    $check_result = pg_query_params($conn, $check_query, array($codice));

    if (pg_num_rows($check_result) > 0) {
        $update_query = "UPDATE esami SET nome=$2, anno=$3, semestre=$4, cfu=$5, ssd=$6, lingua=$7, tipo=$8, ambito=$9, link=$10, descrizione=$11, corso=$12 WHERE codice=$1";
        $update_result = pg_query_params($conn, $update_query, array($codice, $nome, $anno, $semestre, $cfu, $ssd, $lingua, $tipo, $ambito, $link, $desc, $corso));
        if (!$update_result) {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento dell\'esame: ' . pg_last_error($conn)]);
            exit;
        }
        echo json_encode(['success' => true, 'message' => 'Esame aggiornato con successo!']);
        pg_free_result($check_result);
        pg_free_result($update_result);
        pg_close($conn);
        exit;
    }else{
        $insert_query = "INSERT INTO esami (codice, nome, anno, semestre, cfu, ssd, lingua, tipo, ambito, link, descrizione, corso) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)";
        $insert_result = pg_query_params($conn, $insert_query, array($codice, $nome, $anno, $semestre, $cfu, $ssd, $lingua, $tipo, $ambito, $link, $desc, $corso));
        if (!$insert_result) {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'inserimento dell\'esame: ' . pg_last_error($conn)]);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Esame inserito con successo!']);
        pg_free_result($insert_result);
        pg_close($conn);
        exit;
    }

}
?>