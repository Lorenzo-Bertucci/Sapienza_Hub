
    <?php

        header('Content-Type: application/json');
        
        if(isset($_GET['esame'])){
            $esame=$_GET['esame'];
        }
        else{
            echo json_encode(['success' => false, 'message' => 'Errore GET ' . pg_last_error()]);
            exit;
        }

        $conn=pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");
            
        if (!$conn) {
            echo json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . pg_last_error()]);
            exit;
        }

        $query="select utente, testo, TO_CHAR(dat, 'YYYY-MM-DD HH24:MI:SS') AS dat from recensioni where esame='$esame'";
        $result = pg_query($query);

        if (!$result) {
            echo json_encode(['success' => false, 'message' => 'Errore durante il recupero delle recensioni.']);
            exit;
        }

        $recensioni=[];
        while($line=pg_fetch_assoc($result)){
            $recensioni[]=$line;
        }
        pg_free_result($result);
        pg_close($conn);
        echo json_encode(["success"=>true, "recensioni"=> $recensioni]);
    ?>