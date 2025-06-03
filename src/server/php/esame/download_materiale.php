<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Accesso non autorizzato.']);
    exit;
}

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

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

$query = "SELECT materiale FROM materiale_didattico WHERE nomefile=$1 AND esame=$2";
$result = pg_query_params($conn, $query, array($nomefile, $esame));

if ($row = pg_fetch_assoc($result)) {
    $relativeFilePath = $row['materiale'];
    $absoluteFilePath = $_SERVER['DOCUMENT_ROOT'] . $relativeFilePath;
    
    if (file_exists($absoluteFilePath)) {
        $fileData = file_get_contents($absoluteFilePath);
        
        header('Content-Type: application/pdf'); 
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

pg_free_result($result);
pg_close($conn);
?>