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
    <script src=/src/client/js/laurea.js></script>
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
                <button class="bottone"> <strong> Esami</strong></button>
                <button class="bottone"><strong>Materiale Didattico</strong></button>
                <button class="bottone"><strong>Community</strong></button>
            </nav>
        </div>
        <div class="content">
            <div class="esami">
                    
            </div>

            <div class="mat_did">
                prova materiale didattico
            </div>

            <div class="chat">
                prova chat
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
  
  // Imposta per i bottoni della nav-bar
  const navButtons = document.querySelectorAll('.nav-bar .bottone');
  const navSections = {
    "esami": document.querySelector(".esami"),
    "materiale": document.querySelector(".mat_did"),
    "community": document.querySelector(".chat") // Aggiungi la sezione "chat"
  };
  // Imposta "esami" come default
  setupButtonGroup(navButtons, "esami", navSections);
  
});
</script>
</body>
</html>