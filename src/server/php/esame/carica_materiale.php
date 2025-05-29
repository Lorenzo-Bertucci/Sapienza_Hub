<?php
header('Content-Type: application/json');
session_start();

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user_id'];
    $nome_file = pg_escape_string($conn, $_POST['nomefile']);
    $esame = pg_escape_string($conn, $_POST['esame']);
            
    if (isset($_FILES['materiale']) && $_FILES['materiale']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['materiale']['tmp_name'];
        $fileType = $_FILES['materiale']['type'];

        if ($fileType === 'application/pdf') {
            // Definisce la cartella di destinazione relativa a src/server/database/materiale
            $uploadDir = __DIR__ . "/../../database/materiale/";
            // Crea un nome file univoco per evitare conflitti
            $fileName = basename($_FILES['materiale']['name']);
            $uniqueName = uniqid() . "_" . $fileName;
            $destination = $uploadDir . $uniqueName;
            
            if (move_uploaded_file($fileTmpPath, $destination)) {
                // Memorizza il percorso relativo nel database
                $filePath = "/src/server/database/materiale/" . $uniqueName;
                $insert_query = "INSERT INTO materiale_didattico (id_utente, materiale, nomefile, esame) VALUES ($1, $2, $3, $4)";
                $insert_result = pg_query_params($conn, $insert_query, array($id, $filePath, $nome_file, $esame));
            
                if (!$insert_result) {
                    echo json_encode(['success' => false, 'message' => pg_last_error($conn)]);
                    exit;
                }
                echo json_encode(['success' => true, 'message' => 'Materiale caricato con successo!']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Errore nel salvataggio del file.']);
                exit;
            }
        }
        else {
            echo json_encode(['success' => false, 'message' => 'Il file deve essere in formato PDF.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Nessun file caricato o errore durante il caricamento.']);
        exit;
    }
}

pg_close($conn);
?>