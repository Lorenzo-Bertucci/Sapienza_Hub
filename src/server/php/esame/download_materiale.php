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

// Esegue la query per recuperare il percorso del materiale didattico
$query = "SELECT materiale FROM materiale_didattico WHERE nomefile=$1 AND esame=$2";
$result = pg_query_params($conn, $query, array($nomefile, $esame));

if ($row = pg_fetch_assoc($result)) {
    // Il campo "materiale" contiene il percorso relativo del file
    $relativeFilePath = $row['materiale'];
    // Costruisce il percorso assoluto del file
    $absoluteFilePath = $_SERVER['DOCUMENT_ROOT'] . $relativeFilePath;
    
    if (file_exists($absoluteFilePath)) {
        $fileData = file_get_contents($absoluteFilePath);
        
        header('Content-Type: application/pdf'); // Imposta il tipo MIME
        header('Content-Disposition: inline; filename="' . $nomefile . '"');
        header('Content-Length: ' . strlen($fileData));
        
        echo $fileData;
    } else {
        http_response_code(404);
        echo "File non trovato.";
    }
} else {
    http_response_code(404);
    echo "File non trovato.";
}

// Chiude la connessione al database
pg_free_result($result);
pg_close($conn);
?>