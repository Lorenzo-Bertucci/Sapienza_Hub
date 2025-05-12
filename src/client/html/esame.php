<?php
session_start();
$id=isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/src/client/">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/esame.css">
    <script src="js/esame.js"></script>
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
            <div class="dati">
                
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
            <div class="barra">
                <button class="bottone">Professori</button>
                <button class="bottone">Materiale Didattico</button>
                <button class="bottone">Recensioni</button>
            </div>
        </div>

        <div class="content">
            <div class="professori">
               
            </div>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>

            <div class="mat-did">
                <div class="form-container">
                    <h3>Condividi il tuo materiale didattico:</h3>
                    <form class="materiale-form" enctype="multipart/form-data" onsubmit="inviaMateriale(event, <?php echo $id; ?>)">
                        <input type="text" name="nomefile" placeholder="Inserire il nome del file" required>
                        <label for="pdf"><br>Inserire qui il materiale in formato pdf:</label>
                        <input type="file" id="pdf" name="materiale" accept="application/pdf" required>
                        <button>Invia</button>
                    </form>
                </div>

                <div class="materiale">

                </div>
                
            </div>
            
            <div class="recensioni">
                <div class="form-container">
                    <h3>Aggiungi la tua recensione</h3>
                    <form class="recensione-form" onsubmit="inviaRecensione(event, <?php echo $id; ?>)">
                        <textarea name="testo" placeholder="Scrivi la tua recensione" required></textarea>
                        <button>Invia</button>
                    </form>
                </div>
                
                <div class="rec-esami">

                </div>
            </div>
                    
            <?php else: ?>
            <div class="mat-did">
                <div class="mat" style="height:100%;">
                    <h1> Devi effettuare il login per vedere questa sezione</h1>
                </div>
            </div>
            <div class="recensioni">
                <div class="mat" style="height:100%;">
                    <h1> Devi effettuare il login per vedere questa sezione</h1>
                </div>
                
            </div>

            <?php endif; ?>

            

        </div>
        
        <footer>
            <div class="footer">
                <p>&copy; 2025 SapienzHub. Tutti i diritti riservati.</p>
            </div>
        </footer>
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
                    "professori": "professori",
                    "materiale didattico": "mat-did",
                    "recensioni": "recensioni"
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
            const navButtons = document.querySelectorAll('.barra .bottone');
            const navSections = {
                "professori": document.querySelector(".professori"),
                "mat-did": document.querySelector(".mat-did"),
                "recensioni": document.querySelector(".recensioni")
            };

            // Imposta "professori" come default, ma controlla l'hash nell'URL
            setupButtonGroup(navButtons, "professori", navSections);
        });
    </script>
</body>
</html>