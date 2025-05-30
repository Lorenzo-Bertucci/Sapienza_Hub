<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utente - SapienzHub</title>
    <base href="/src/client/">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/utente.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="js/utente.js"></script>
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
            <div class="auth-buttons">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <div class="profile-container">
                        
                        <img src="<?php echo isset($_SESSION['profile_img']) ? $_SESSION['profile_img'] : 'assets/utente.png'; ?>" class="profile-icon" onclick="window.location.href='html/dashboard.php'">
    
                        <div class="dropdown-menu">
                            <a href="html/dashboard.php" style="text-decoration: none;"><button class="dropdown-item">Dashboard</button></a>
                            <a href="../server/php/auth/logout.php"><button class="dropdown-item">Logout</button></a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="html/login.php"><button class="login-btn">Accedi</button></a>
                    <a href="html/register.php"><button class="register-btn">Registrati</button></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="main">
            <div class="foto">  
                <img src="assets/utente.png" alt="Foto utente">
            </div>

            <div class="info">
            </div>
        </div>

        <div class="content">
            <div class="scheda">
                <h1>Recensioni</h1>
                <div class="recensioni" >
                    <div class="rec-esami">

                    </div>
                    <div class="rec-professori">
                    

                    </div>
                </div>
            </div>

            <div class="scheda">
                <h1>Materiale Didattico</h1>
                <div class="file" >
                    

                </div>
            </div>
        </div>
    </div>

        
</body>
</html>