<?php
        header('Content-Type: application/json');

        $conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

        if (!$conn) {
            echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = pg_escape_string($conn, $_POST['nome']);
            $testo = pg_escape_string($conn, $_POST['testo']);
            //$date = date('Y-m-d H:i:s');
            $materia=pg_escape_string($conn, $_POST['materia']);

            $insert_query = "INSERT INTO $materia (nome, testo) VALUES ('$nome', '$testo')";
            $insert_result = pg_query($conn, $insert_query);

            if ($insert_result) {
                echo json_encode(['success' => true, 'message' => 'Recensione aggiunta con successo!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Errore durante l\'inserimento della recensione.' . pg_last_error($conn)]);
            }
            exit;
        }
?>