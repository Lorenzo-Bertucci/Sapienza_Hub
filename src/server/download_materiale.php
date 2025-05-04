<?php

    $conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
        exit;
    }

    $esame = $_GET['esame'];
    $nomefile = $_GET['nomefile'];

    if (!$esame || !$nomefile) {
        http_response_code(400);
        echo "Parametri mancanti.";
        exit;
    }

    $query="select materiale from materiale_didattico where nomefile='$nomefile' and esame='$esame'";
    $result=pg_query($query);
    
    if ($row = pg_fetch_assoc($result)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($nomefile) . '.pdf"');
        echo pg_unescape_bytea($row['materiale']);
    } else {
        http_response_code(404);
        echo "File non trovato.";
    }

    pg_close($conn);


?>