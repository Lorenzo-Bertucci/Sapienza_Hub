<?php
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

if (!isset($_GET['id']) ) {
    echo json_encode(['success' => false, 'message' => 'Codice del professore non specificato.']);
    exit;
}

$id = pg_escape_string($conn, $_GET['id']);

$query = "SELECT * FROM professori WHERE id = $1";
$result = pg_query_params($conn, $query, array($id));

if (!$result || pg_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Professore non trovato.']);
    exit;
}

$professore = pg_fetch_assoc($result);

pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'info' => $professore]);
?>