<?php
header('Content-Type: application/json');
    
$conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
        
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
    exit;
}

// Sanitizza il codice dell'esame per prevenire SQL injection
$esame = pg_escape_string($conn, $_GET['esame']);
$query="SELECT r.id,u.nome AS utente, u.foto , testo, dat, id_utente FROM recensioni_esami r JOIN utenti u ON r.id_utente=u.id WHERE r.codice=$1 ORDER BY dat DESC";
$result = pg_query_params($conn, $query, array($esame));

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero delle recensioni.']);
    exit;
}

$recensioni=[];
while($line=pg_fetch_assoc($result)){
    $recensioni[]=$line;
}

// Chiudi la connessione al database
pg_free_result($result);
pg_close($conn);

// Restituisci i dati come JSON
echo json_encode(["success"=>true, "recensioni"=> $recensioni]);
?>