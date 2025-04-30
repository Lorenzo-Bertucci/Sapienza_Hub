<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fondamenti di matematica - SapienzHub</title>
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
                <a href="html/index.html">
                    <h1>SapienzHub</h1>
                </a>
                <nav>
                    <ul>
                        <li><a href="html/corsi.html">Corsi</a></li>
                        <li><a href="html/professori.html">Professori</a></li>
                        <li><a>Contatti</a></li>
                    </ul>
                </nav>
            </div>
            <div class="auth-buttons">
                <a href="html/login.html"><button class="login-btn">Accedi</button></a>
                <a href="html/register.html"><button class="register-btn">Registrati</button></a>
            </div>
        </div>

        <div class="main">
            <div class="dati">
                <p><b>CFU: </b>9</p>
                <p><b>SSD: </b>MAT/09</p>
                <p><b>Lingua: </b>🇮🇹</p>
                <p><b>Tipo: </b>Attività formativa di base</p>
                <p><b>Ambito Disciplinare: </b>Matmatica, informatica e statistica</p>
                <a href="https://corsidilaurea.uniroma1.it/it/view-course-details/2024/31810/20220331104432/47c0ec84-11c0-4c84-bd7c-fab4d3c00649/2e537653-64f5-4bab-a05f-f04329673a16/684a57ec-3179-4dd4-8c48-551b594a65e3/18f2dfe0-4afa-480f-a4b5-6d33279ff705?guid_cv=2e537653-64f5-4bab-a05f-f04329673a16&current_erogata=47c0ec84-11c0-4c84-bd7c-fab4d3c00649"
                    style="color: black;" target="_blank">
                    <strong>Link pagina esame</strong>
                </a>

            </div>
            <div class="desc">
                <br>
                <h1>Fondamenti di Matematica</h1><br>
                <p>
                    Il corso ha l’obiettivo di fornire agli studenti una solida introduzione al calcolo differenziale e
                    integrale, con particolare attenzione all’analisi matematica delle funzioni reali di una variabile
                    reale.<br>
                    Gli studenti acquisiranno le conoscenze teoriche fondamentali, come i concetti di limite,
                    convergenza,
                    derivata e integrale, e impareranno ad applicarle per risolvere problemi classici della matematica,
                    come lo studio qualitativo dei grafici di funzione, la risoluzione di problemi di massimo e minimo,
                    il calcolo di aree mediante integrali e la soluzione di semplici equazioni differenziali.<br>
                    Il corso mira inoltre a sviluppare la capacità di costruire dimostrazioni matematiche rigorose,
                    utilizzando un linguaggio appropriato e una terminologia precisa. Gli studenti saranno incoraggiati
                    a
                    esprimere autonomamente ragionamenti deduttivi e ad affinare le proprie abilità comunicative in
                    ambito scientifico. <br>
                    Infine, particolare enfasi sarà posta sull’apprendimento attivo e consapevole della matematica,
                    stimolando
                    un atteggiamento critico e autonomo nello studio, anche attraverso il confronto con docenti e
                    compagni.
                </p>


            </div>
            <div class="barra">
                <button class="bottone">Professori</button>
                <button class="bottone" onclick="openMat('fondamentidimatematicamat')">Materiale Didattico</button>
                <button class="bottone">Community</button>
                <button class="bottone" onclick="openRec('fondamentidimatematicarec')">Recensioni</button>
            </div>
        </div>

        <div class="content">
            <div class="professori">
                <h2>Primo Canale</h2>
                <div class="prof">
                    <a href="html/professori/saverio_salzo.html">
                        <h3>Saverio Salzo</h3>
                    </a>
                </div>
                <br><br>
                <h2>Secondo Canale</h2>
                <div class="prof">
                    <a href="html/professori/nicola_galesi.html">
                        <h3>Nicola Galesi</h3>
                    </a>
                </div>
            </div>
            <div class="mat-did">
                <div class="form-container">
                    <h3>Condividi il tuo materiale didattico:</h3>
                    <form class="materiale-form" enctype="multipart/form-data" onsubmit="inviaMateriale(event, 'fondamentidimatematicamat')">
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
                    <form class="recensione-form" onsubmit="inviaRecensione(event, 'fondamentidimatematicarec')">
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

</body>

</html>