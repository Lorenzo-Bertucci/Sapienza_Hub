<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professori - Sapienza Hub</title>
    <base href="/src/client/">
    <link rel="stylesheet" href="css/professori.css">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <script src="js/professori.js"></script>
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
        <div class="foto">
            <h3>Professori</h3>
        </div>
        <div class="search-bar">
            <img src="assets/lente.png" alt="search" class="search" >
            <input type="text" id="search-input" placeholder="Cerca il Professore">
            <button>Cerca</button>
        </div>
        <div class="content">
            
        </div>
    </div>
    
</body>
</html>