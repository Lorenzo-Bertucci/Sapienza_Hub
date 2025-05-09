<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SapienzHub</title>
    <base href="/src/client/">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/pagina_prof.css">
    <script src="js/home_prof.js"></script>
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

        <div class="main">
            <div class="foto">  
                <img src="assets/utente.png" alt="Ingegneria Informatica e Automatica">
            </div>

            <div class="desc">
                <?php
                    $conn = pg_connect("host=localhost port=5433 dbname=sapienzhub user=postgres password=Postgre*1");

                    if (!$conn) {
                        echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
                        exit;
                    }
                    
                    if (!isset($_GET['id']) ) {
                        echo json_encode(['success' => false, 'message' => 'Codice del professore non specificato.']);
                        exit;
                    }
                    
                    $id = pg_escape_string($conn, $_GET['id']);
                    
                    // Query per recuperare le informazioni dell'esame
                    $query = "SELECT * FROM professori WHERE id = $1";
                    $result = pg_query_params($conn, $query, array($id));
                    
                    if (!$result || pg_num_rows($result) === 0) {
                        echo json_encode(['success' => false, 'message' => 'Professore non trovato.']);
                        exit;
                    }
                    
                    // Recupera i dati del professore
                    $professore = pg_fetch_assoc($result);
                    
                    // Chiudi la connessione al database
                    pg_free_result($result);
                    pg_close($conn);

                    echo "<h1>{$professore['nome']}</h1>
                        <p>
                            <strong>E-mail</strong>: <a href='mailto:{$professore['email']}' style='color: black;'>{$professore['email']}</a>
                            <br>
                            <strong>Dipartimento</strong>: {$professore['dip']}
                            <br>
                            <strong>SSD</strong>: {$professore['ssd']}
                            <br>
                            <a href='{$professore['link_']}' style='color: black;' target='_blank'>
                                <strong>Link sito</strong>
                            </a>
                        </p>";
                ?>
            </div>
            <nav class="nav-bar">
                <button class="bottone"> <strong>Esami</strong></button>
                <button class="bottone"><strong>Recensioni</strong></button>
            </nav>
        </div>
        <div class="content">
            <div class="esami">
                <!--
                <div class="card">
                    <a href="html/corsi/ing_inf/esami/fondamenti_di_matematica.php">
                        <h3>Fondamenti di Matematica</h3>
                        <p>~Ingegneria informatica e automatica</p>
                    </a>
                </div>
                -->
            </div>
            <div class="recensioni">
                <h3>recensioni</h3>
            </div>

            
        </div>
        <div class="footer">
            <p>&copy; 2025 SapienzHub. Tutti i diritti riservati.</p>
        </div>
    </div>

</body>
</html>