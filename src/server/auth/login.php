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

$qgestione="SELECT * FROM gestione where utente=$1";
$result= pg_query_params($conn, $qgestione, array($email));
$utente = pg_fetch_array($result, null, PGSQL_ASSOC);
if ($utente) {
    if (!password_verify($password, $utente['password'])) {
        echo json_encode(['success' => false, 'message' => 'Password errata.']);
        exit;
    }
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $tuple['id'];
    $_SESSION['user_email'] = $email;
    
    echo json_encode(['success' => false, 'message' => 'Login Gestore confermato', 'gestione' => true]);

    exit;
}


// Controlla se l'email esiste
$q1 = "SELECT * FROM utenti WHERE email=$1";
$result = pg_query_params($conn, $q1, array($email));
$tuple = pg_fetch_array($result, null, PGSQL_ASSOC);
if (!$tuple) {
    echo json_encode(['success' => false, 'message' => 'Email non presente.', 'gestione' => false]);
    exit;
}

// Verifica la password
if (!password_verify($password, $tuple['password'])) {
    echo json_encode(['success' => false, 'message' => 'Password errata.', 'gestione' => false]);
    exit;
}

// Login riuscito
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = $tuple['id'];
$_SESSION['user_email'] = $email;
list($nome, $cognome) = explode(' ', $tuple['nome'], 2);
$_SESSION['user_nome'] = $nome;
$_SESSION['user_cognome'] = $cognome;

// Salva l'immagine profilo in sessione (base64 se presente, altrimenti percorso default)
if (!empty($tuple['foto'])) {
    $_SESSION['profile_img'] = 'data:image/png;base64,' . base64_encode(pg_unescape_bytea($tuple['foto']));
} else {
    $_SESSION['profile_img'] = 'assets/utente.png';
}

// Liberazione della memoria e chiusura della connessione
pg_free_result($result);
pg_close($conn);

echo json_encode(['success' => true, 'message' => 'Login effettuato con successo.', 'gestione' => false]);
exit;
?>
