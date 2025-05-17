<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $destinatario = "sapienzhub@gmail.com";
    $oggetto="Assistenza";
    $messaggio = $_POST['contenutomail'];
    $mittente = $_SESSION['user_email'];
    mail($destinatario, $oggetto, $messaggio, $mittente);
}

?>