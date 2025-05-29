<?php
header('Content-Type: application/json');
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}
$result = pg_query($conn, "SELECT codice, nome FROM corsi ORDER BY nome");
$corsi = [];
while ($row = pg_fetch_assoc($result)) {
    $corsi[] = $row;
}
echo json_encode($corsi);
pg_free_result($result);
pg_close($conn);
?>