<?php
header('Content-Type: application/json');
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}
$result = pg_query($conn, "SELECT id, nome FROM professori ORDER BY nome");
$professori = [];
while ($row = pg_fetch_assoc($result)) {
    $professori[] = $row;
}
echo json_encode($professori);
pg_free_result($result);
pg_close($conn);
?>