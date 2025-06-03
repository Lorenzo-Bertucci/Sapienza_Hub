<?php
header('Content-Type: application/json');

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Id professore non specificato.']);
    exit;
}

$id = pg_escape_string($conn, $_GET['id']);

$query = "SELECT ins.nome_esame as nome, es.corso as corso, es.codice as codice FROM insegna ins, esami es WHERE ins.id_professore = $1 and ins.codice_esame=es.codice";
$result = pg_query_params($conn, $query, array($id));

if (!$result || pg_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Nessun esame trovato per questo professore']);
    exit;
}

$esame = pg_fetch_all($result);

pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'data' => $esame]);
?>