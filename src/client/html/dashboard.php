<?php
session_start();

// Controlla se l'utente è loggato
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    // Reindirizza alla pagina di login se non è loggato
    header('Location: index.html');
    exit;
}

// Recupera le informazioni dell'utente dalla sessione
$user_email = $_SESSION['user_email'] ?? 'Email non disponibile';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SapienzHub</title>
    <base href="/src/client/">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Benvenuto nella tua Dashboard</h1>
        </div>
        <div class="content">
            <h2>Informazioni personali</h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?></p>
            <p>Qui puoi gestire le tue informazioni personali, visualizzare i tuoi corsi preferiti e molto altro!</p>
        </div>
        <div class="footer">
            <a href="../server/logout.php">Logout</a>
        </div>
    </div>
</body>
</html>