<?php
session_start();
header('Content-Type: application/json');

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
if(!$conn){
    echo json_encode(['success'=>false, 'message'=>'Errore di connessione al database']);
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK){
         $imgTmpPath = $_FILES['profilePic']['tmp_name'];
         $imgData = file_get_contents($imgTmpPath);
         // Salva il blob nel database
         $img_profilo = pg_escape_bytea($conn,$imgData);
         $user_id = $_SESSION['user_id'];
         $q = "UPDATE utenti SET foto = $1 WHERE id = $2";
         $res = pg_query_params($conn, $q, array($img_profilo, $user_id));
         if($res){
            // Aggiorna anche la sessione con la nuova immagine (in formato base64)
            $_SESSION['profile_img'] = 'data:image/png;base64,' . base64_encode(pg_unescape_bytea($img_profilo));
            echo json_encode(['success'=>true, 'message'=>'Immagine aggiornata correttamente']);
         } else {
            echo json_encode(['success'=>false, 'message'=>'Errore durante l\'aggiornamento']);
         }
    } else{
         echo json_encode(['success'=>false, 'message'=>'Nessun file caricato o errore durante il caricamento']);
    }
} else{
   echo json_encode(['success'=>false, 'message'=>'Metodo non valido']);
}
pg_close($conn);
?>