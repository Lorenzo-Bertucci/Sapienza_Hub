<?php
header('Content-Type: application/json'); 

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$email = pg_escape_string($conn, $_POST['inputEmail']);

$q1 = "SELECT * FROM utenti WHERE email=$1";
$result = pg_query_params($conn, $q1, array($email));

if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo json_encode(['success' => false, 'message' => 'Email già registrata.']);
    pg_free_result($result);
    exit;
} else {
    $nome = pg_escape_string($conn, $_POST['inputNome']) . ' ' . pg_escape_string($conn, $_POST['inputCognome']);
    $password = password_hash($_POST['inputPassword'], PASSWORD_DEFAULT);
    $img_profilo = null;
    $studia=pg_escape_string($conn, $_POST['inputCorso']);
    
    
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        
        $uploadDir = __DIR__ . "/../../database/utenti/";
        
        
        $imgName = basename($_FILES['profilePic']['name']);
        $uniqueName = uniqid() . "_" . $imgName;
        $destination = $uploadDir . $uniqueName;
        
        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $destination)) {
            
            $img_profilo = "/src/server/database/utenti/" . $uniqueName;
        } else {
            echo json_encode(['success' => false, 'message' => 'Errore nel salvataggio dell\'immagine.']);
            exit;
        }
    }
    
    $q2 = "INSERT INTO utenti (email, nome, password, foto,studia) VALUES ($1, $2, $3, $4, $5)";
    $result = pg_query_params($conn, $q2, array($email, $nome, $password, $img_profilo, $studia));

    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Errore durante la registrazione.']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Registrazione avvenuta con successo.']);
    exit;
}

pg_close($conn);
?>
