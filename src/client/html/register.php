<?php
session_start();

// Se l'utente è già loggato, reindirizza altrove
if (isset($_SESSION['logged_in'])) {
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
        <div class="content">
            <h3><a href="html/index.php" style="text-decoration: none; color: inherit;">SapienzHub</a></h3>
            <form id="registerForm" method="POST" class="login-form" enctype="multipart/form-data" onsubmit="inviaReg(event)">
                <input type="text" id="nome" name="inputNome" placeholder="Inserisci il tuo nome" required>
                <br>
                <input type="text" id="cognome" name="inputCognome" placeholder="Inserisci il tuo cognome" required>
                <br>
                <input type="email" id="email" name="inputEmail" placeholder="Inserisci la mail istituzionale" required>
                <br>
                <input type="password" id="password" name="inputPassword" placeholder="Inserisci la tua password" required>
                <br>
                <select id="corso" name="inputCorso" required>
                    <option value="">Seleziona il tuo corso di laurea</option>
                </select>
                <br>
                <div class="profile-row">
                    <label for="profilePic" class="profile-label">
                        <span>Imposta la tua immagine profilo</span>
                    </label>
                    <input type="file" id="profilePic" name="profilePic" accept="image/*" style="display:none;">
                    <img id="preview" src="assets/utente.png" alt="Preview immagine profilo" class="profile-preview" tabindex="0">
                </div>
                <button type="submit" class="login-btn">Registrati</button>
            </form>
            <p>Hai già un account? <a href="html/login.php">Accedi qui</a></p>
        </div>
    </div>
</body>
</html>