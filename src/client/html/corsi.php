<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corsi di Laurea - Sapienza Hub</title>
    <base href="/src/client/">
    <link rel="stylesheet" href="css/corsi.css">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <script src="js/corsi_di_laurea.js"></script>
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
                        <li><a >Contatti</a></li>
                    </ul>
                </nav>
            </div>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <div class="profile-container">
                        <button class="profile-btn" onclick="window.location.href='html/dashboard.php'">
                            <img src="assets/utente.png" class="profile-icon">
                        </button>
                        <div class="dropdown-menu">
                            <a href="html/dashboard.php" style="text-decoration: none;"><button class="dropdown-item">Dashboard</button></a>
                            <a href="../server/logout.php"><button class="dropdown-item">Logout</button></a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="html/login.php"><button class="login-btn">Accedi</button></a>
                    <a href="html/register.php"><button class="register-btn">Registrati</button></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="content">
            <div class="search-bar">
                <img src="assets/lente.png" alt="search" class="search" >
                <input type="text" id="search-input" placeholder="Cerca il tuo corso di laurea">
                <button>Cerca</button>
            </div>
        </div>
        <div class="footer">
            <p>&copy; 2025 Sapienza Hub. Tutti i diritti riservati.</p>
        </div>
    </div>
</body>
</html>