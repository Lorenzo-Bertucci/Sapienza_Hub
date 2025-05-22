<?php
header('Content-Type: application/json');

$conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id=pg_escape_string($conn, $_POST['id']);

    $check_query = "SELECT * FROM professori WHERE id = $1";
    $check_result = pg_query_params($conn, $check_query, array($id));

    if (pg_num_rows($check_result) == 0) {
        echo json_encode(['success' => false, 'message' => 'Il professore non esiste!']);
        pg_free_result($check_result);
        pg_close($conn);
        exit;
    }else{

        $query = "DELETE FROM professori WHERE id = $1";
        $result = pg_query_params($conn, $query, array($id));
        if (!$result) {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'eliminazione del professore: ' . pg_last_error($conn)]);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Professore eliminato con successo!']);
        pg_free_result($result);
        pg_close($conn);
        exit;
    }

}
?>