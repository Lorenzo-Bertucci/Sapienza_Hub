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

function openRec(){
    const recensioniDiv = document.querySelector(".recensioni");
    fetch('/src/server/recensioni_esami.php')
        .then(response => {
            if (!response.ok) {
                throw new Error("Errore nel caricamento delle recensioni.");
            }
            return response.text();
        })
        .then(data => {
            console.log(data);
            recensioniDiv.innerHTML = data; 
        })
        .catch(error => {
            console.error("Errore:", error);
            recensioniDiv.innerHTML = "<p>Impossibile caricare le recensioni.</p>";
        });
    }