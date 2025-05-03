<?php
        header('Content-Type: application/json');

        $conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = pg_escape_string($conn, $_POST['nome']);
            $nome_file= pg_escape_string($conn, $_POST['nomefile']);
            $date = date('Y-m-d H:i:s');
            $materia=pg_escape_string($conn, $_POST['materia']);


            if (isset($_FILES['materiale']) && $_FILES['materiale']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['materiale']['tmp_name'];
                $fileType = $_FILES['materiale']['type'];


                if ($fileType === 'application/pdf') {
                    $fileContentRaw = file_get_contents($fileTmpPath);
                    $fileContent = pg_escape_bytea($conn, $fileContentRaw);

                    $insert_query = "INSERT INTO $materia (nome, materiale, dat, nomefile) VALUES ('$nome', '$fileContent', '$date', '$nome_file')";
                    $insert_result = pg_query($conn, $insert_query);
        
                    if ($insert_result) {
                        echo json_encode(['success' => true, 'message' => 'Materiale caricato con successo!']);
                    } else {
                        
                        echo json_encode(['success' => false, 'message' => 'Errore durante l\'inserimento del materiale: ' . pg_last_error($conn)]);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Il file caricato non è un PDF.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Errore durante il caricamento del file.']);
            }
        exit;
        }
?>