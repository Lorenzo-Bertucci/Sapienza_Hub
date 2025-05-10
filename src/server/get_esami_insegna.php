<?php
header('Content-Type: application/json');

// Connessione al database PostgreSQL
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

// Recupera il codice del corso di laurea dall'URL
if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Id professore non specificato.']);
    exit;
}

$id = pg_escape_string($conn, $_GET['id']);

// Query per recuperare le informazioni dell'esame
$query = "SELECT ins.nome_esame as nome, es.corso as corso, es.codice as codice FROM insegna ins, esami es WHERE ins.id_professore = $1 and ins.codice_esame=es.codice";
$result = pg_query_params($conn, $query, array($id));

if (!$result || pg_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Esami non trovati.']);
    exit;
}

// Recupera i dati delgli esami
$esame = pg_fetch_all($result);


// Chiudi la connessione al database
pg_free_result($result);
pg_close($conn);

// Restituisci i dati come JSON
echo json_encode(['success' => true, 'data' => $esame]);
?>