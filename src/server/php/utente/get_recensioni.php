<?php
header('Content-Type: application/json');
session_start();

// Connessione al database PostgreSQL
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

// Recupero dell'ID utente dalla sessione
$user_id = pg_escape_string($conn, $_GET['id']);
// Recupero del campo 'tipo' dall'URL
$tipo = pg_escape_string($conn, $_GET['tipo']);

if ($tipo === 'esami') {
    $query = "SELECT r.id,r.testo,r.dat,e.codice,e.nome FROM recensioni_esami r JOIN esami e on r.codice=e.codice WHERE id_utente=$1 ORDER BY dat";
} elseif ($tipo === 'professori') {
    $query = "SELECT r.id,r.testo,r.dat,r.id_professore,p.nome FROM recensioni_professori r JOIN professori p on r.id_professore=p.id  WHERE id_utente=$1 ORDER BY dat";
} else {
    echo json_encode(['success' => false, 'message' => 'Tipo non valido.']);
    exit;
}

// Query per recuperare i corsi di laurea
$result = pg_query_params($conn, $query,array($user_id));

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore durante il recupero dei dati.']);
    exit;
}

// Creazione dell'array dei corsi
$recensioni = [];
while ($row = pg_fetch_assoc($result)) {
    $recensioni[] = $row;
}

// Liberazione della memoria e chiusura della connessione
pg_free_result($result);
pg_close($conn);

// Restituzione dei dati come JSON
echo json_encode(['success' => true, 'recensioni' => $recensioni]);
?>