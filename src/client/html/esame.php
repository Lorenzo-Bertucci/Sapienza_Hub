<?php
session_start();
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
                
            </div>
            <div class="barra">
                <button class="bottone">Professori</button>
                <button class="bottone" onclick="openMat()">Materiale Didattico</button>
                <button class="bottone">Community</button>
                <button class="bottone" onclick="openRec()">Recensioni</button>
            </div>
        </div>

        <div class="content">
            <div class="professori">
                <!--
                <h2>Primo Canale</h2>
                <div class="prof">
                    <a href="html/professori/marco_schaerf.php">
                    <h3>Marco Schaerf</h3>
                    </a>
                </div>
                <div class="prof">
                    <a href="html/professori/antonella_poggi.php">
                    <h3>Antonella Poggi</h3>
                    </a>
                </div>
                <br><br>
                <h2>Secondo Canale</h2>
                <div class="prof">
                    <a href="html/professori/giuseppe_santucci.php">
                        <h3>Giuseppe Santucci</h3>
                        </a>
                </div>
                -->
            </div>
            <div class="mat-did">
                <div class="form-container">
                    <h3>Condividi il tuo materiale didattico:</h3>
                    <form class="materiale-form" enctype="multipart/form-data" onsubmit="inviaMateriale(event)">
                        <input type="text" name="nome" placeholder="Il tuo nome" required>
                        <input type="text" name="nomefile" placeholder="Inserire il nome del file" required>
                        <label for="pdf"><br>Inserire qui il materiale in formato pdf:</label>
                        <input type="file" id="pdf" name="materiale" accept="application/pdf" required>
                        <button>Invia</button>
                    </form>
                </div>

                <div class="materiale">

                </div>
                
            </div>
            <div class="community">
                
            </div>
            <div class="recensioni">
                <div class="form-container">
                    <h3>Aggiungi la tua recensione</h3>
                    <form class="recensione-form" onsubmit="inviaRecensione(event)">
                        <input type="text" name="nome" placeholder="Il tuo nome" required>
                        <textarea name="testo" placeholder="Scrivi la tua recensione" required></textarea>
                        <button>Invia</button>
                    </form>
                </div>
                
                <div class="php">

                </div>
            </div>

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
            // Imposta il bottone di default
            buttons.forEach(btn => {
              if (btn.textContent.trim().toLowerCase().includes(defaultString)) {
                btn.classList.add('active');
              } else {
                btn.classList.remove('active');
              }
            });
            // Imposta la visualizzazione di default: mostra la sezione del bottone default
            for (const condition in sections) {
              sections[condition].style.display = condition === defaultString ? 'block' : 'none';
            }
            // Gestione del click: mostra la sezione corrispondente e rende attivo il bottone cliccato
            buttons.forEach(btn => {
              btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const btnText = btn.textContent.trim().toLowerCase();
                for (const condition in sections) {
                  sections[condition].style.display = btnText.includes(condition) ? 'block' : 'none';
                }
              });
            });
          }
          
          // Imposta per i bottoni della barra
          const navButtons = document.querySelectorAll('.barra .bottone');
          const navSections = {
            "professori": document.querySelector(".professori"),
            "materiale": document.querySelector(".mat-did"),
            "community": document.querySelector(".community"),
            "recensioni": document.querySelector(".recensioni")
          };
          // Imposta "professori" come default
          setupButtonGroup(navButtons, "professori", navSections);

        });
    </script>
</body>
</html>