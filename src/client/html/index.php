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
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <script src=/src/client/js/index.js></script>
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
            <h2>"Benvenuto su SapienzHub"</h2>
            <p>Trova il tuo corso di laurea e inizia la tua esperienza universitaria insieme a noi</p>
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Cerca il tuo corso di laurea">
                <button id="search-button">Cerca</button>
            </div>
        </div>
        <div class="aside">
            <div class="benvenuto">
                <h3>🎓 Cos'è SapienzHub?</h3>
                <p> <strong>SapienzHub</strong> nasce con un obiettivo semplice ma ambizioso: rendere la vita universitaria più facile, connessa e collaborativa per tutti gli studenti della Sapienza.
                    <br>
                    Qui puoi esplorare i corsi di laurea, consultare gli esami, scoprire opinioni reali e sincere di altri studenti, condividere materiali utili e creare connessioni con chi sta affrontando il tuo stesso percorso.
                    <br>
                    <strong>È una piattaforma fatta da studenti, per studenti</strong>: perché crediamo che l’unione e la condivisione siano la chiave per affrontare al meglio il mondo universitario.
                    <br>
                    Accedi, crea il tuo profilo e inizia a costruire la tua esperienza universitaria insieme a noi!
                </p>
            </div>
        </div>
        <div class="content">
            <a class="card" href="html/corsi.php">
                <h3>📚 Corsi di Laurea</h3>
                <p>
                    Hai presente quel momento in cui non sai nemmeno che esami ti aspettano, figuriamoci da che prof sono tenuti? Tranquillo, ci pensiamo noi.
                </p>
                <p>
                    Con la nostra sezione <strong>Corsi di Laurea</strong> puoi:
                </p>
                <ul>
                    <li>Scoprire tutti i corsi di laurea disponibili alla Sapienza</li>
                    <li>Vedere gli esami associati, con info utili e aggiornate</li>
                    <li>Leggere le recensioni di altri studenti che ci sono già passati...</li>
                    <li>Trovare (o caricare) materiali didattici come appunti, slide e dispense già pronti all’uso</li>
                </ul>
                <p>
                    Che tu sia una matricola persa nel caos o un laureando in crisi esistenziale, qui trovi tutto quello che ti serve: chiaro, ordinato e senza sbatti.
                </p>
            </a>
            <a class="card" href="html/professori.php">
                <h3>👨‍🏫 Professori</h3>
                <p>
                    Chi c’è dietro a ogni esame? Un nome su un piano di studi non basta. 
                    Ora potrai finalmente dare un volto (e una reputazione) a chi tiene i corsi.
                    <br>
                    Con la nostra sezione <strong>Professori</strong> puoi:
                </p>
                <ul>
                    <li>Visualizzare le schede dei docenti con tutte le loro informazioni</li>
                    <li>Leggere recensioni sincere lasciate da altri studenti</li>
                    <li>Capire a cosa vai incontro prima ancora di sostenere l’esame</li>
                    <li>Trovare materiali didattici collegati ai loro corsi</li>
                </ul>
                <p>
                    Niente più “E questo prof com’è?”, adesso hai tutto quello che ti serve per farti un’idea in un unico posto.
                </p>
            </a>
        </div>
    </div>
</body>
</html>