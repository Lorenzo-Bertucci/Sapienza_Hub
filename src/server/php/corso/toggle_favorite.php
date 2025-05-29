<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Devi effettuare il login.']);
    exit;
}

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$codice = $data['codice'];
$corso = $data['corso'];
$action = $data['action'];
$user_id = $_SESSION['user_id'];

if ($action === "add") {
    $query = "INSERT INTO preferiti_corsi(id_utente,codice,nome) VALUES ($1, $2,$3)";
    $result = pg_query_params($conn, $query, array($user_id, $codice, $corso));
    $message = $result ? "Corso aggiunto ai preferiti." : "Errore durante l'aggiunta ai preferiti.";
} elseif ($action === "remove") {
    $query = "DELETE FROM preferiti_corsi WHERE id_utente = $1 AND codice = $2";
    $result = pg_query_params($conn, $query, array($user_id, $codice));
    $message = $result ? "Corso rimosso dai preferiti." : "Errore durante la rimozione dai preferiti.";
} else {
    echo json_encode(['success' => false, 'message' => 'Azione non valida.']);
    exit;
}

pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'message' => $message]);
?>