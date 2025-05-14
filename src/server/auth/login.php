<?php
header('Content-Type: application/json'); // Imposta il tipo di contenuto come JSON
     
session_start();                               

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

// Recupera i dati dal form
$email = pg_escape_string($conn,$_POST['inputEmail']);
$password = pg_escape_string($conn,$_POST['inputPassword']);

// Controlla se l'email esiste
$q1 = "SELECT * FROM utenti WHERE email=$1";
$result = pg_query_params($conn, $q1, array($email));
$tuple = pg_fetch_array($result, null, PGSQL_ASSOC);
if (!$tuple) {
    echo json_encode(['success' => false, 'message' => 'Email non presente.']);
    exit;
}

// Verifica la password
if (!password_verify($password, $tuple['password'])) {
    echo json_encode(['success' => false, 'message' => 'Password errata.']);
    exit;
}

// Login riuscito
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = $tuple['id'];
$_SESSION['user_email'] = $email;
list($nome, $cognome) = explode(' ', $tuple['nome'], 2);
$_SESSION['user_nome'] = $nome;
$_SESSION['user_cognome'] = $cognome;

// Liberazione della memoria e chiusura della connessione
pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'message' => 'Login effettuato con successo.']);
exit;
?>
