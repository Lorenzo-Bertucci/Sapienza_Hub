<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - SapienzHub</title>
    <base href="/src/client/">
    <link rel="stylesheet" href="css/settings.css">
    <script src="/src/client/js/settings.js"></script>
</head>
<body>
    <div class="container">
        <div class="laterale">
            <b>Gestione Database</b>
            <ul>
                <li onclick="gestisciAzione(this.textContent)">Aggiungi/Aggiorna Corso di Studi</li>
                <li onclick="gestisciAzione(this.textContent)">Rimuovi Corso di Studi</li>
                <li onclick="gestisciAzione(this.textContent)">Aggiungi/Aggiorna Esame</li>
                <li onclick="gestisciAzione(this.textContent)">Rimuovi Esame</li>
                <li onclick="gestisciAzione(this.textContent)">Aggiungi/Aggiorna Professore</li>
                <li onclick="gestisciAzione(this.textContent)">Rimuovi Professore</li>
                <li onclick="gestisciAzione(this.textContent)">Aggiungi Utente Privilegiato</li>
                <li onclick="gestisciAzione(this.textContent)">Rimuovi Utente Privilegiato</li>
            </ul>
            <button class="back" onclick="window.location.href='/src/client/html/index.php'">Torna alla Home</button>
        </div>
        <div class="tool">
            <!-- Qui viene caricato il form per la gestione scelto -->
            <h2>Seleziona un'azione dalla lista</h2>
        </div>
    </div>
</body>
</html>