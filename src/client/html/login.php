<?php
session_start();

// Controlla se l'utente è loggato
if (isset($_SESSION['logged_in'])) {
    // Reindirizza alla pagina di login se è loggato
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SapienzHub</title>
    <base href="/src/client/">
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <script src="js/login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="content">
            <h3><a href="html/index.php" style="text-decoration: none; color: inherit;">SapienzHub</a></h3>
            <form id="loginForm" method="POST" class="login-form" onsubmit="inviaLogin(event)">
                <input type="email" name="inputEmail" placeholder="Inserisci la mail istituzionale" required>
                <br>
                <input type="password" name="inputPassword" placeholder="Inserisci la tua password" required>
                <br>
                <button type="submit" class="login-btn">Accedi</button>
            </form>
            <p>Non hai un account? <a href="html/register.php">Registrati qui</a></p>
        </div>
    </div>
</body>
</html>