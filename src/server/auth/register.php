<?php
header('Content-Type: application/json'); // Imposta il tipo di contenuto come JSON

// Connessione al database PostgreSQL
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$email = pg_escape_string($conn,$_POST['inputEmail']);

$q1 = "SELECT * FROM utenti WHERE email=$1";
$result = pg_query_params($conn, $q1, array($email));

if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo json_encode(['success' => false, 'message' => 'Email già registrata.']);
    exit;
} else {
    $nome= pg_escape_string($conn,$_POST['inputNome']) . ' ' . pg_escape_string($conn,$_POST['inputCognome']);
    $password = password_hash($_POST['inputPassword'], PASSWORD_DEFAULT);
    $q2 = "INSERT INTO utenti (email, nome, password) VALUES ($1, $2, $3)";
    $result = pg_query_params($conn, $q2, array($email, $nome, $password));

    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Errore durante la registrazione.']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Registrazione avvenuta con successo.']);
    exit;
}

pg_free_result($result);
pg_close($conn);
?>
