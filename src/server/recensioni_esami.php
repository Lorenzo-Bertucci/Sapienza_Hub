
    <?php

        header('Content-Type: application/json');
        
        if(isset($_GET['materia'])){
            $materia=$_GET['materia'];
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

        $query="select nome, testo, TO_CHAR(dat, 'YYYY-MM-DD HH24:MI:SS') AS dat from $materia";
        $result = pg_query($query);

        if (!$result) {
            echo json_encode(['success' => false, 'message' => 'Errore durante il recupero delle recensioni.']);
            exit;
        }

        $recensioni=[];
        /*echo "<div class='titolo'><b>Dicono di questo esame:</b></div>";
        echo "<div class='rec'>";*/
        while($line=pg_fetch_assoc($result)){
            /*$nome=$line['nome'];
            $rec=$line['testo'];
            echo "<br><h4>~$nome:</h4> <p>$rec</p>";*/
            $recensioni[]=$line;
        }
        //echo "</div>";
        pg_free_result($result);
        pg_close($conn);
        echo json_encode(["success"=>true, "recensioni"=> $recensioni]);
    ?>