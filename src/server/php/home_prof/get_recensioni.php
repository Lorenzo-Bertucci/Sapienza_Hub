<?php
header('Content-Type: application/json');

$conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
    
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
    exit;
}

if(!isset($_GET['id'])){
    echo json_encode(['success' => false, 'message' => 'Errore GET ' . pg_last_error()]);
    exit;
}

$prof_id = pg_escape_string($conn, $_GET['id']);

$query="SELECT r.id,u.nome as utente, testo, dat,id_utente,u.foto FROM recensioni_professori r JOIN utenti u ON r.id_utente=u.id  where r.id_professore=$1 ORDER BY dat DESC";
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