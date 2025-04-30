<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corsi di Laurea - Sapienza Hub</title>
    <base href="/src/client/">
    <link rel="stylesheet" href="css/corsi.css">
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
                        <li><a >Contatti</a></li>
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
        <div class="content">
            <div class="search-bar">
                <img src="assets/lente.png" alt="search" class="search" >
                <input type="text" id="search-input" placeholder="Cerca il tuo corso di laurea">
                <button>Cerca</button>
            </div>
                <h1>Lista dei Corsi di Laurea</h1>
                <div class="card">
                    <a href="html/corsi/ing_inf/ing_inf.php">
                        <h3>💻 Ingegneria Informatica</h3>
                        <p>~Corso Triennale</p>
                    </a>
                </div>
                <div class="card">
                    <a>
                        <h3>🩺 Medicina e Chirurgia</h3>
                        <p>~Corso Unico</p>
                    </a>
                </div>
                <div class="card">
                    <a>
                        <h3>⚖️ Giurisprudenza</h3>
                        <p>~Corso Unico</p>
                    </a>
                </div>
                <div class="card">
                    <a >
                        <h3>🏛️ Scienze Politiche</h3>
                        <p>~Corso Triennale</p>
                    </a>
                </div>
                <div class="card">
                    <a >
                        <h3>⚙️ Ingegneria Meccanica</h3>
                        <p>~Corso Triennale</p>
                    </a>
                </div>
                <div class="card">
                    <a >
                        <h3>🧠 Psicologia</h3>
                        <p>~Corso Triennale</p>
                    </a>
                </div>
                <div class="card">
                    <a >
                        <h3>🏗️ Architettura</h3>
                        <p>~Corso Triennale</p>
                    </a>
                </div>
                <div class="card">
                    <a >
                        <h3>🧬 Biotecnologie</h3>
                        <p>~Corso Triennale</p>
                    </a>
                </div>
                <div class="card">
                    <a >
                        <h3>⚗️ Chimica Industriale</h3>
                        <p>~Corso Triennale</p>
                    </a>
                </div>
                <div class="card">
                    <a >
                        <h3>➗ Matematica</h3>
                        <p>~Corso Triennale</p>
                    </a>
                </div>
                <div class="card">
                    <a >
                        <h3>🔭 Fisica</h3>
                        <p>~Corso Triennale</p>
                    </a>
                </div>
                <div class="card">
                    <a >
                        <h3>📚 Lettere Moderne</h3>
                        <p>~Corso Triennale</p>
                    </a>
                </div>
                <div class="card">
                    <a >
                        <h3>🤖 Informatica</h3>
                        <p>~Corso Triennale</p>
                    </a>
                </div>
        </div>
        <div class="footer">
            <p>&copy; 2025 Sapienza Hub. Tutti i diritti riservati.</p>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get("search")?.toLowerCase() || "";
            const cards = document.querySelectorAll(".card");
            const searchInput = document.getElementById("search-input");
            const searchButton = document.querySelector(".search-bar button");

            // Imposta il valore della barra di ricerca con il testo della query
            if (searchQuery) {
                searchInput.value = searchQuery; // Mostra il testo nella barra di ricerca
            }

            // Funzione per filtrare i corsi
            function filterCourses(query) {
                cards.forEach(card => {
                    const courseName = card.querySelector("h3").textContent.toLowerCase();
                    if (courseName.includes(query)) {
                        card.style.display = "flex"; // Mostra la card
                    } else {
                        card.style.display = "none"; // Nascondi la card
                    }
                });
            }

            // Filtra i corsi se c'è un termine di ricerca
            if (searchQuery) {
                filterCourses(searchQuery);
            }

            // Aggiungi un evento per la barra di ricerca nella pagina corsi.php
            searchButton.addEventListener("click", function () {
                const query = searchInput.value.toLowerCase();
                filterCourses(query);
            });

            searchInput.addEventListener("keydown", function (event) {
                if (event.key === "Enter") {
                    const query = searchInput.value.toLowerCase();
                    filterCourses(query);
                }
            });
        });
    </script>
</body>
</html>