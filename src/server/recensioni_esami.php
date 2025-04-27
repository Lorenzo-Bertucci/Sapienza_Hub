<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recensioni</title>
    <base href="/src/client/">
    <link rel="stylesheet" href="css/esame.css">
</head>
<body>
    <?php

        //header("Content-Type: text/html; charset=UTF-8");

        // Simula il contenuto delle recensioni (puoi sostituirlo con dati reali da un database)
        $conn=pg_connect("host=localhost port=5432 dbname=provadb user=postgres password=Postgre*1");

        $query="select * from recensioni";
        if (!$conn) {
            echo "<br>Errore di connessione al database: " . pg_last_error();
        }
        $result = pg_query($query);
        echo "<div class='main'>";
        while($line=pg_fetch_array($result, null, PGSQL_ASSOC)){
            $nome=$line['nome'];
            $rec=$line['testo'];
            echo "<br><h3>$nome:</h3> $rec";
            /*foreach($line as $colvalue){
                echo "<br>$colvalue<br>";
            }*/
        }
        echo "</div>";
        pg_free_result($result);
        pg_close($dbconn);
    ?>
</body>
</html>


