<?php
header('Content-Type: application/json');


$conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
    exit;
}

$esame = pg_escape_string($conn,$_GET['esame']);

$query="SELECT u.nome AS utente, nomefile, dat FROM materiale_didattico m JOIN utenti u ON m.id_utente=u.id where esame=$1 ORDER BY dat DESC";
$result = pg_query_params($conn, $query, array($esame));
if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero del materiale.']);
    exit;
}

$materiale=[];
while($line=pg_fetch_assoc($result)){
    $materiale[]=$line;
}

pg_free_result($result);
pg_close($conn);

echo json_encode(["success"=>true, "materiale"=> $materiale]);
?>