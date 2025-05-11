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
                        <a href="../server/logout.php"><button class="dropdown-item">Logout</button></a>
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
                  <h3><b>Corsi di laurea</b></h3>

                </div>
                <div class="esami">
                  <h3><b>Esami</b></h3>

                </div>
            </div>
            <div class="recensioni" >
              <h2>Qui puoi trovare le recensioni che hai lasciato su esami e professori</h2>
              <div class="rec-esami">
                  <h3><b>Esami</b></h3>

                </div>
                <div class="rec-professori">
                  <h3><b>Professori</b></h3>

                </div>

            </div>
            <div class="file" >
                <h2>Qui puoi trovare i file che hai caricato e condiviso</h2>

            </div>
          </div>
        
        <div class="footer">
            <p>&copy; 2025 SapienzHub. Tutti i diritti riservati.</p>
        </div>
    </div>
<script>
        document.addEventListener("DOMContentLoaded", function() {
        
        /**
         * Imposta un gruppo di bottoni con:
         * - Un bottone "default" (che contiene defaultString nel testo) attivo all'avvio
         * - Gestione del click per mostrare la sezione corrispondente
         *
         * @param {NodeList} buttons - I bottoni del gruppo
         * @param {string} defaultString - Stringa (in minuscolo) da cercare per impostare il default
         * @param {Object} sections - Un oggetto in cui le chiavi sono stringhe da cercare (in minuscolo)
         *                            e i valori sono gli elementi da mostrare/nascondere
         */
        function setupButtonGroup(buttons, defaultString, sections) {
                // Mappa tra i testi dei bottoni e i nomi delle sezioni
                const buttonToSectionMap = {
                    "preferiti": "preferiti",
                    "le tue recensioni": "recensioni",
                    "i tuoi file": "file"
                };

                // Controlla l'hash nell'URL
                if (!window.location.hash) {
                    // Se non c'è un hash, imposta quello di default
                    window.location.hash = `#${defaultString}`;
                }
                const currentHash = window.location.hash.substring(1).toLowerCase();

                // Imposta il bottone attivo e la sezione visibile in base all'hash
                buttons.forEach(btn => {
                    const btnText = btn.textContent.trim().toLowerCase();
                    if (buttonToSectionMap[btnText] === currentHash) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });

                for (const condition in sections) {
                    sections[condition].style.display = condition === currentHash ? 'block' : 'none';
                }

                // Gestione del click: mostra la sezione corrispondente e aggiorna l'URL
                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        buttons.forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        const btnText = btn.textContent.trim().toLowerCase();
                        const sectionId = buttonToSectionMap[btnText];
                        for (const condition in sections) {
                            sections[condition].style.display = condition === sectionId ? 'block' : 'none';
                        }
                        // Aggiorna l'URL con il nome della sezione attiva
                        window.location.hash = sectionId;
                    });
                });
            }
        
        // Imposta per i bottoni della barra
        const navButtons = document.querySelectorAll('.nav-bar .bottone');
        const navSections = {
          "preferiti": document.querySelector(".preferiti"),
          "recensioni": document.querySelector(".recensioni"),
          "file": document.querySelector(".file")
        };
        // Imposta "professori" come default
        setupButtonGroup(navButtons, "preferiti", navSections);

      });

</script>
</body>
</html>