<?php
header('Content-Type: application/json');
session_start();

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$tipo = pg_escape_string($conn, $_GET['tipo']);

if ($tipo === 'esami') {
    $query = "SELECT r.id,r.testo,r.dat,e.codice,e.nome FROM recensioni_esami r JOIN esami e on r.codice=e.codice WHERE id_utente=$1 ORDER BY dat";
} elseif ($tipo === 'professori') {
    $query = "SELECT r.id,r.testo,r.dat,r.id_professore,p.nome FROM recensioni_professori r JOIN professori p on r.id_professore=p.id  WHERE id_utente=$1 ORDER BY dat";
} else {
    echo json_encode(['success' => false, 'message' => 'Tipo non valido.']);
    exit;
}

$result = pg_query_params($conn, $query,array($user_id));

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero dei dati.']);
    exit;
}

$recensioni = [];
while ($row = pg_fetch_assoc($result)) {
    $recensioni[] = $row;
}

pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'recensioni' => $recensioni]);
?>