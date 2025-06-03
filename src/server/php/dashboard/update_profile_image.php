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
         $uploadDir = __DIR__ . "/../../database/utenti/";
         $imgName = basename($_FILES['profilePic']['name']);
         $uniqueName = uniqid() . "_" . $imgName;
         $destination = $uploadDir . $uniqueName;
         
         if(move_uploaded_file($_FILES['profilePic']['tmp_name'], $destination)){
              $img_profilo = "/src/server/database/utenti/" . $uniqueName;
              $user_id = $_SESSION['user_id'];
              $q = "UPDATE utenti SET foto = $1 WHERE id = $2";
              $res = pg_query_params($conn, $q, array($img_profilo, $user_id));
              if($res){
                  $_SESSION['profile_img'] = $img_profilo;
                  echo json_encode(['success'=>true, 'message'=>'Immagine aggiornata correttamente']);
              } else {
                  echo json_encode(['success'=>false, 'message'=>'Errore durante l\'aggiornamento nel database']);
              }
         } else{
              echo json_encode(['success'=>false, 'message'=>'Errore nel salvataggio dell\'immagine']);
         }
    } else{
         echo json_encode(['success'=>false, 'message'=>'Nessun file caricato o errore durante il caricamento']);
    }
} else{
   echo json_encode(['success'=>false, 'message'=>'Metodo non valido']);
}
pg_close($conn);
?>