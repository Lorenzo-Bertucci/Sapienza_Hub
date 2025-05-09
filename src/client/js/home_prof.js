function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
  }

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

    function loadEsami(){
        const id=getUrlParameter('id');
        const divEsame = document.querySelector(".esami");
        fetch(`/src/server/get_esami_insegna.php?id=${encodeURIComponent(id)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Errore durante il caricamento degli esami.");
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
                    const esami = data.data;
                    console.log(Array.isArray(esami));
                    esami.forEach(esame =>{
                        const card=document.createElement('div');
                        card.classList.add("card");
                        card.innerHTML=`
                                <a href="/src/client/html/esame.php?corso=${esame.corso}&codice=${esame.codice}">
                                    <h3>${esame.nome}</h3>
                                </a>`;
                        divEsame.appendChild(card);
                    });

            }
            else{
                console.log(data.message);
                divEsame.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";
            }    
            })
            .catch(error => {
                console.error("Errore:", error);
                divEsame.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";
            });
    }
  
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
    loadEsami();

    setupButtonGroup(navButtons, "esami", navSections);
  
  
  });