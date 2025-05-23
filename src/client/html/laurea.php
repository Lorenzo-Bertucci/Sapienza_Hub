<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <base href="/src/client/">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/laurea.css">
    <script src=/src/client/js/corso.js></script>
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
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <div class="profile-container">
                        
                        <img src="<?php echo isset($_SESSION['profile_img']) ? $_SESSION['profile_img'] : 'assets/utente.png'; ?>" class="profile-icon" onclick="window.location.href='html/dashboard.php'">
    
                        <div class="dropdown-menu">
                            <a href="html/dashboard.php" style="text-decoration: none;"><button class="dropdown-item">Dashboard</button></a>
                            <a href="../server/auth/logout.php"><button class="dropdown-item">Logout</button></a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="html/login.php"><button class="login-btn">Accedi</button></a>
                    <a href="html/register.php"><button class="register-btn">Registrati</button></a>
                <?php endif; ?>
            </div>
        </div>

        <div class="main">
            <div class="aside">
                <div class="foto">

                </div>
                <div class="info">
                    
                </div>
            </div>
            <div class="desc">
                <div class="nome">
                    <h1 id="corso-nome"></h1>
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                        <button id="favorite-btn" class="favorite-btn" title="Aggiungi ai preferiti" onclick="favorite()">
                            &#9734;
                        </button>
                    <?php endif; ?>
                </div>
                <p id="corso-descrizione"></p>
            </div>
            <nav class="nav-bar">
                <button class="bottone active"><strong>Esami</strong></button>
            </nav>
        </div>
        <div class="content">
            <div class="esami">
                    
            </div>

        </div>
        <div class="footer">
            <p>&copy; 2025 SapienzHub. Tutti i diritti riservati.</p>
        </div>
    </div>
</body>
</html>