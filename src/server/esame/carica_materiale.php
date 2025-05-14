<?php
header('Content-Type: application/json');
session_start();

$conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user_id'];
    $nome_file= pg_escape_string($conn, $_POST['nomefile']);
    $esame=pg_escape_string($conn, $_POST['esame']);
            
    if (isset($_FILES['materiale']) && $_FILES['materiale']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['materiale']['tmp_name'];
        $fileType = $_FILES['materiale']['type'];

        if ($fileType === 'application/pdf') {
            $fileContentRaw = file_get_contents($fileTmpPath);
            $fileContent = pg_escape_bytea($conn, $fileContentRaw);

            $insert_query = "INSERT INTO materiale_didattico (id_utente, materiale, nomefile, esame) VALUES ($1, $2, $3, $4)";
            $insert_result = pg_query_params($conn, $insert_query, array($id, $fileContent, $nome_file, $esame));
        
            if (!$insert_result) {
                echo json_encode(['success' => false, 'message' => pg_last_error($conn)]);
                exit;
            }
            echo json_encode(['success' => true, 'message' => 'Materiale caricato con successo!']);
            exit;
        }
        else {
            echo json_encode(['success' => false, 'message' => 'Il file deve essere in formato PDF.']);
            exit;
        }
    }
}

pg_free_result($insert_result);
pg_close($conn);
?>