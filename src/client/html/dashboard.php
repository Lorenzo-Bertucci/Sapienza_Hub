<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

$user_email = $_SESSION['user_email'] ?? 'Email non disponibile';
$user_nome = $_SESSION['user_nome'] ?? 'Nome non disponibile';
$user_cognome = $_SESSION['user_cognome'] ?? 'Cognome non disponibile';
$user_studia = $_SESSION['studia'] ?? 'Corso non disponibile';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SapienzHub</title>
    <base href="/src/client/">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                    </ul>
                </nav>
            </div>
        </div>

        <div class="side-bar">
            <div class="desc">
                <img src="<?php echo isset($_SESSION['profile_img']) ? $_SESSION['profile_img'] : 'assets/utente.png'; ?>" class="profile-icon">
                <button id="change-profile-btn" class="change-profile-btn" onclick="modificaFoto()">
                    <i class="fa fa-camera"></i> Modifica Immagine
                </button>
                <h2> I tuoi dati:</h2>
                <p>
                    <strong>Nome:</strong> <?php echo htmlspecialchars($user_nome); ?><br>
                    <strong>Cognome:</strong> <?php echo htmlspecialchars($user_cognome); ?><br>
                    <strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?><br>                   
                    <strong>Corso di laurea:</strong> <?php echo htmlspecialchars($user_studia); ?><br>
                </p>
            </div>
            <nav class="nav-bar">
                <button class="bottone">
                    <i class="fa fa-star"></i> <strong>Preferiti</strong>
                </button>
                <button class="bottone">
                    <i class="fa fa-comment"></i> <strong>Le tue recensioni</strong>
                </button>
                <button class="bottone">
                    <i class="fa fa-file"></i> <strong>I tuoi file</strong>
                </button>
                <span></span>
                <span></span>
                <button class="bottone logout" onclick="logout()">
                    <i class="fa fa-sign-out-alt"></i> <strong>Logout</strong>
                </button>
            </nav>
        </div>
        <div class="main">
            <h1>Ciao <?php echo htmlspecialchars($_SESSION['user_nome'] ?? 'Utente'); ?></span><span style="font-weight: normal;">, benvenuto nella tua Dashboard!</span></h1>
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
        </div>
        <div class="navigation-mobile">
            <div class="item"><a href="html/index.php"><i class="fa fa-home"></i>Home</a></div>
            <div class="item"><a href="html/corsi.php"><i class="fa fa-book"></i>Corsi</a></div>
            <div class="item"><a href="html/professori.php"><i class="fa fa-users"></i>Professori</a></div>
            <div class="item"><a href="html/dashboard.php"><i class="fa fa-user"></i>Dashboard</a></div>
        </div>

    </div>
</body>
</html>