<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gustavo Posta - SapienzHub</title>
    <base href="/src/client/">
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/pagina_prof.css">
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
                <h1>Gustavo Posta</h1>
                <p>
                    <strong>E-mail</strong>: <a href="mailto:gustavo.posta@uniroma1.it" style="color: black;">gustavo.posta@uniroma1.it</a>
                    <br>
                    <strong>Dipartimento</strong>: MATEMATICA
                    <br>
                    <strong>SSD</strong>: MAT/06
                    <br>
                    <a href="https://corsidilaurea.uniroma1.it/user/15638" style="color: black;" target="_blank">
                        <strong>Link sito</strong>
                    </a>
                </p>
            </div>
            <nav class="nav-bar">
                <button class="bottone"> <strong>Esami</strong></button>
                <button class="bottone"><strong>Recensioni</strong></button>
            </nav>
        </div>
        <div class="content">
            <div class="esami">
                <div class="card">
                    <a href="html/corsi/ing_inf/esami/elementi_di_calcolo_delle_probabilita_e_statistica.php">
                        <h3>Elementi di calcolo delle probabilità e statistica</h3>
                        <p>~Ingegneria informatica e automatica</p>
                    </a>
                </div>
                <div class="card">
                    <a>
                        <h3>Probabilità I</h3>
                        <p>~Matematica</p>
                    </a>
                </div>
            </div>
            <div class="recensioni">
                <h3>recensioni</h3>
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
    "recensioni": document.querySelector(".recensioni"),
  };
  // Imposta "esami" come default
  setupButtonGroup(navButtons, "esami", navSections);
});
</script>
</body>
</html>