<?php
// Avvia la sessione per gestire l'autenticazione dell'utente
session_start();

// Verifica se l'utente è autenticato, altrimenti restituisce un errore 401
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Accesso non autorizzato.']);
    exit;
}

// Connessione al database PostgreSQL
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

// Controlla se la connessione al database è riuscita
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
    exit;
}

// Recupera e valida i parametri GET 'esame' e 'nomefile'
$esame = pg_escape_string($conn, $_GET['esame']);
$nomefile = pg_escape_string($conn, $_GET['nomefile']);

if (!$esame || !$nomefile) {
    http_response_code(400);
    echo "Parametri mancanti.";
    exit;
}

// Esegue la query per recuperare il materiale didattico
$query = "select materiale from materiale_didattico where nomefile='$nomefile' and esame='$esame'";
$result = pg_query($query);

// Se il file esiste, lo restituisce come download
if ($row = pg_fetch_assoc($result)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($nomefile) . '.pdf"');
    echo pg_unescape_bytea($row['materiale']);
} else {
    // Restituisce un errore 404 se il file non viene trovato
    http_response_code(404);
    echo "File non trovato.";
}

// Chiude la connessione al database
pg_close($conn);
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Accesso non autorizzato.']);
    exit;
}
$conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
    exit;
}

$esame = pg_escape_string($conn, $_GET['esame']);
$nomefile = pg_escape_string($conn, $_GET['nomefile']);

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