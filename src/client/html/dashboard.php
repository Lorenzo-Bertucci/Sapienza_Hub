<?php
session_start();

// Controlla se l'utente è loggato
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    // Reindirizza alla pagina di login se non è loggato
    header('Location: index.php');
    exit;
}

// Recupera le informazioni dell'utente dalla sessione
$user_email = $_SESSION['user_email'] ?? 'Email non disponibile';
$user_nome = $_SESSION['user_nome'] ?? 'Nome non disponibile';
$user_cognome = $_SESSION['user_cognome'] ?? 'Cognome non disponibile';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingegneria Informatica e Automatica - SapienzHub</title>
    <base href="/src/client/">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/dashboard.js"></script>
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
            <div class="auth-buttons">
                <div class="profile-container">
                    <button class="profile-btn" onclick="window.location.href='html/dashboard.php'">
                        <img src="assets/utente.png" class="profile-icon">
                    </button>
                    <div class="dropdown-menu">
                        <a href="html/dashboard.php" style="text-decoration: none;"><button class="dropdown-item">Dashboard</button></a>
                        <a href="../server/auth/logout.php"><button class="dropdown-item">Logout</button></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main">
            <div class="desc">
                <h1>Ciao <?php echo htmlspecialchars($_SESSION['user_nome'] ?? 'Utente'); ?></span><span style="font-weight: normal;">, benvenuto nella tua Dashboard!</span></h1>
                <h2> I tuoi dati:</h2>
                <p>
                    <strong>Nome:</strong> <?php echo htmlspecialchars($user_nome); ?><br>
                    <strong>Cognome:</strong> <?php echo htmlspecialchars($user_cognome); ?><br>
                    <strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?><br>                   
                </p>
            </div>
            <nav class="nav-bar">
                <button class="bottone"> <strong>Preferiti</strong></button>
                <button class="bottone"><strong>Le tue recensioni</strong></button>
                <button class="bottone"><strong>I tuoi file</strong></button>
            </nav>
        </div>
        <div class="content">
            <div class="preferiti">
                <h2>Qui puoi trovare i tuoi corsi ed esami preferiti </h2>
                <div class="corsi">
                  

                </div>
                <div class="esami">
                  

                </div>
            </div>
            <div class="recensioni" >
                <h2>Qui puoi trovare le recensioni che hai lasciato su esami e professori</h2>
                <div class="rec-esami">

                </div>
                <div class="rec-professori">
                  

                </div>

            </div>
            <div class="file" >
                

            </div>
          </div>
        
        <div class="footer">
            <p>&copy; 2025 SapienzHub. Tutti i diritti riservati.</p>
        </div>
    </div>
</body>
</html>