<?php
header('Content-Type: application/json'); // Imposta il tipo di contenuto come JSON
     
session_start();                               

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}


$email = pg_escape_string($conn,$_POST['inputEmail']);
$password = pg_escape_string($conn,$_POST['inputPassword']);

$qgestione="SELECT * FROM gestione where utente=$1";
$result= pg_query_params($conn, $qgestione, array($email));
$utente = pg_fetch_array($result, null, PGSQL_ASSOC);
if ($utente) {
    if (!password_verify($password, $utente['password'])) {
        echo json_encode(['success' => false, 'message' => 'Password errata.']);
        exit;
    }    
    echo json_encode(['success' => false, 'message' => 'Login Gestore confermato', 'gestione' => true]);

    exit;
}


$q1 = "SELECT u.id, u.nome, c.nome AS corso,u.foto,u.password FROM utenti u JOIN corsi c ON u.studia=c.codice WHERE email=$1";
$result = pg_query_params($conn, $q1, array($email));
$tuple = pg_fetch_array($result, null, PGSQL_ASSOC);
if (!$tuple) {
    echo json_encode(['success' => false, 'message' => 'Email non presente.', 'gestione' => false]);
    exit;
}


if (!password_verify($password, $tuple['password'])) {
    echo json_encode(['success' => false, 'message' => 'Password errata.', 'gestione' => false]);
    exit;
}

$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = $tuple['id'];
$_SESSION['user_email'] = $email;
list($nome, $cognome) = explode(' ', $tuple['nome'], 2);
$_SESSION['user_nome'] = $nome;
$_SESSION['user_cognome'] = $cognome;
$_SESSION['studia'] = $tuple['corso'];


if (!empty($tuple['foto'])) {
    $_SESSION['profile_img'] = $tuple['foto'];
} else {
    $_SESSION['profile_img'] = 'assets/utente.png';
}


pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'message' => 'Login effettuato con successo.', 'gestione' => false,'user_id' => $_SESSION['user_id']]);
?>

