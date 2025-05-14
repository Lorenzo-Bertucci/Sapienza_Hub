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
    <title>Registrati - SapienzHub</title>
    <base href="/src/client/">
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <script src="js/register.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="h-left">
                <a href="html/index.php">
                    <h1>SapienzHub</h1>
                </a>
                <nav>
                    <ul>
                        <li><a href="html/corsi.php">Corsi</a></li>
                        <li><a href="html/professori.php">Professori</a></li>
                        <li><a>Contatti</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="content">
            <h3>SapienzHub</h3>
            <form id="registerForm" method="POST" class="login-form" onsubmit="inviaReg(event)">
                <input type="text" id="nome" name="inputNome" placeholder="Inserisci il tuo nome" required>
                <br>
                <input type="text" id="cognome" name="inputCognome" placeholder="Inserisci il tuo cognome" required>
                <br>
                <input type="email" id="email" name="inputEmail" placeholder="Inserisci la mail istituzionale" required>
                <br>
                <input type="password" id="password" name="inputPassword" placeholder="Inserisci la tua password" required>
                <br>
                <button type="submit" class="login-btn">Registrati</button>
            </form>
            <p>Hai già un account? <a href="html/login.php">Accedi qui</a></p>
        </div>
        <div class="footer">
            <p>&copy; 2025 Sapienza Hub. Tutti i diritti riservati.</p>
        </div>
    </div>
</body>
</html>