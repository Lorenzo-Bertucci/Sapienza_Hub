<?php    
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contatti - SapienzHub</title>
    <base href="/src/client/">
    <link rel="stylesheet" href="css/contatti.css">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
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
                        <li><a href="html/contatti.php">Contatti</a></li>
                    </ul>
                </nav>
            </div>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <div class="profile-container">
                        
                        <img src="<?php echo isset($_SESSION['profile_img']) ? $_SESSION['profile_img'] : 'assets/utente.png'; ?>" class="profile-icon" onclick="window.location.href='html/dashboard.php'">
    
                        <div class="dropdown-menu">
                            <a href="html/dashboard.php" style="text-decoration: none; ">
                                <button class="dropdown-item">
                                    <i class="fa-solid fa-house" style="margin-right: 8px;"></i>Dashboard
                                </button>
                            </a>
                            <a href="../server/php/auth/logout.php" style="text-decoration: underline; text-decoration-color: rgb(170, 33, 33);">
                                <button class="dropdown-item">
                                    <i class="fa-solid fa-right-from-bracket" style="margin-right: 8px;"></i>Logout
                                </button>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="html/login.php"><button class="login-btn">Accedi</button></a>
                    <a href="html/register.php"><button class="register-btn">Registrati</button></a>
                <?php endif; ?>
            </div>
        </div>

        <div class="content">
            <div class="contatti">
                <!--<h3>Le nostre mail:</h3>
                <p>bertucci.2044683@studenti.uniroma1.it</p>
                <p>crisci.2060281@studenti.uniroma1.it</p>-->
                <form class="form-mail" action="/src/server/php/contatti/mail.php" method="POST" onsubmit="return confermaInvio()">
                    <h2>Hai dei problemi? Inviaci una mail:</h2>
                    <textarea name="contenutomail" placeholder="Inserire il testo della mail" required></textarea>
                    <button>Invia</button>
                </form>

            </div>
        </div>

        <footer>
            <div class="footer">
                <p>&copy; 2025 SapienzHub. Tutti i diritti riservati.</p>
            </div>
        </footer>
    </div>

    <script>
        function confermaInvio() {
            return confirm("Sei sicuro di voler inviare questa mail?");
        }
    </script>
</body>
</html>