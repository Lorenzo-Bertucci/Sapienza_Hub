<?php
// filepath: /Users/lorenzo/Documents/Sapienza_Hub/src/server/esame/report_review.php
session_start();
header('Content-Type: application/json');

// Configura la connessione al database
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
if(!$conn){
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['review_id']) && is_numeric($_POST['review_id'])) {
        $review_id = intval($_POST['review_id']);

        // Aggiorna il campo segnalazioni nella tabella recensioni_esami
        $update_query = "UPDATE recensioni_esami SET segnalazioni = segnalazioni + 1 WHERE id = $1";
        $result = pg_query_params($conn, $update_query, array($review_id));
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Grazie per il tuo contributo']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Errore durante la segnalazione o recensione non trovata. Riprova.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID recensione mancante o non valido.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metodo non valido.']);
}
pg_close($conn);
?>