<?php

    header('Content-Type: application/json');
    
    if(isset($_GET['id'])){
        $prof_id=$_GET['id'];
    }
    else{
        echo json_encode(['success' => false, 'message' => 'Errore GET ' . pg_last_error()]);
        exit;
    }

    $conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
        
    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
        exit;
    }

    $query="SELECT u.nome as utente, testo, dat FROM recensioni_professori r JOIN utenti u ON r.id_utente=u.id  where r.id_professore=$1 ORDER BY dat";
    $result = pg_query_params($conn, $query, array($prof_id));

    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Errore durante il recupero delle recensioni.']);
        exit;
    }

    $recensioni=[];
    while($line=pg_fetch_assoc($result)){
        $recensioni[]=$line;
    }
    pg_free_result($result);
    pg_close($conn);
    echo json_encode(["success"=>true, "recensioni"=> $recensioni]);
?>