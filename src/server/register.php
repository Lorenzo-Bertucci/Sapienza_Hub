<?php
header('Content-Type: application/json'); // Imposta il tipo di contenuto come JSON

// Connessione al database PostgreSQL
$conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

$email = $_POST['inputEmail'];
$q1 = "SELECT * FROM utenti WHERE email=$1";
$result = pg_query_params($conn, $q1, array($email));

if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo json_encode(['success' => false, 'message' => 'Email già registrata.']);
    exit;
} else {
    $nome= $_POST['inputNome'] . ' ' . $_POST['inputCognome'];
    $password = password_hash($_POST['inputPassword'], PASSWORD_DEFAULT);
    $q2 = "INSERT INTO utenti (email, nome, password) VALUES ($1, $2, $3) RETURNING id";
    $result = pg_query_params($conn, $q2, array($email, $nome, $password));

    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Errore durante la registrazione.']);
        exit;
    }

    // Recupera l'ID dell'utente appena registrato
    $user_id = pg_fetch_result($result, 0, 'id');

    // Crea una tabella specifica per i preferiti dell'utente
    $table_name = "preferiti_" . $user_id;

    // Crea la tabella preferiti
    $q3 = "CREATE TABLE $table_name (
        codice VARCHAR(50) NOT NULL,
        nome VARCHAR(255) NOT NULL,
        corso BOOLEAN NOT NULL
    )";
    $create_table_result = pg_query($conn, $q3);

    if (!$create_table_result) {
        echo json_encode(['success' => false, 'message' => 'Errore durante la creazione della tabella preferiti.']);
        exit;
    }
    

    // Aggiungi una funzione per validare i dati prima dell'inserimento
    $q4 = "CREATE OR REPLACE FUNCTION validate_preferiti() RETURNS TRIGGER AS $$
    BEGIN
        IF NEW.corso THEN
            -- Controlla se il codice e il nome esistono nella tabella corsi
            IF NOT EXISTS (SELECT 1 FROM corsi WHERE codice = NEW.codice AND nome = NEW.nome) THEN
                RAISE EXCEPTION 'Il codice e il nome non esistono nella tabella corsi.';
            END IF;
        ELSE
            -- Controlla se il codice e il nome esistono nella tabella esami
            IF NOT EXISTS (SELECT 1 FROM esami WHERE codice = NEW.codice AND nome = NEW.nome) THEN
                RAISE EXCEPTION 'Il codice e il nome non esistono nella tabella esami.';
            END IF;
        END IF;
        RETURN NEW;
    END;
    $$ LANGUAGE plpgsql;";

    // Crea la funzione di validazione
    $validate_function_result = pg_query($conn, $q4);

    if (!$validate_function_result) {
        // Elimina l'utente appena creato
        $delete_user_query = "DELETE FROM utenti WHERE id = $1";
        $delete_user_result = pg_query_params($conn, $delete_user_query, array($user_id));

        if (!$delete_user_result) {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'eliminazione dell\'utente appena creato.']);
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Errore durante la creazione della funzione di validazione.']);
        exit;
    }

    // Aggiungi un trigger per chiamare la funzione di validazione
    $q5 = "CREATE TRIGGER validate_preferiti_trigger
    BEFORE INSERT ON $table_name
    FOR EACH ROW
    EXECUTE FUNCTION validate_preferiti();";

    $trigger_result = pg_query($conn, $q5);

    if (!$trigger_result) {
        $delete_user_query = "DELETE FROM utenti WHERE id = $1";
        $delete_user_result = pg_query_params($conn, $delete_user_query, array($user_id));

        if (!$delete_user_result) {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'eliminazione dell\'utente appena creato.']);
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Errore durante la creazione del trigger di validazione.']);
        exit;
    }


    echo json_encode(['success' => true, 'message' => 'Registrazione avvenuta con successo.']);
    exit;
}
?>
