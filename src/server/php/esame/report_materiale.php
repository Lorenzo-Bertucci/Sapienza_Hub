<?php
session_start();
header('Content-Type: application/json');

$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
if(!$conn){
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['material_id']) && is_numeric($_POST['material_id'])) {
        $review_id = intval($_POST['material_id']);

        $update_query = "UPDATE materiale_didattico SET segnalazioni = segnalazioni + 1 WHERE id = $1";
        $result = pg_query_params($conn, $update_query, array($review_id));
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Grazie per il tuo contributo']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Errore durante la segnalazione o materiale non trovata. Riprova.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID recensione mancante o non valido.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metodo non valido.']);
}
pg_close($conn);
?>