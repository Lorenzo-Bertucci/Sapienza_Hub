function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

function openRec(){
  const recensioniDiv = document.querySelector(".rec-esami");
  const prof_id=getUrlParameter('id');
  fetch(`/src/server/recensioni_professori.php?id=${encodeURIComponent(prof_id)}`)
    .then(response => {
      console.log(response);
        if (!response.ok) {
          throw new Error("Errore nel caricamento delle recensioni.");
        }
        return response.json();
    })
    .then(data => {
      if(data.success){
        recensioniDiv.innerHTML='';
        const tit=document.createElement('div');
        tit.classList.add("titolo");
        tit.innerHTML='<b>Dicono di questo Professore</b>';
        recensioniDiv.appendChild(tit);
        data.recensioni.forEach(recensione=>{
          const rec=document.createElement('div');
          rec.classList.add("rec");
          recensione.dat = new Date(recensione.dat).toLocaleDateString("it-IT", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric"
          });
          rec.innerHTML+=`<div><span class='utente'><strong>~${recensione.utente}</strong></span><span style="margin-left: 10px; font-size: smaller; font-style: italic;"><small><i> ${recensione.dat}</i></small></span></div> <p>${recensione.testo}</p>`;
          recensioniDiv.appendChild(rec);
        });
      }else{
        console.error("Errore nel caricamento dati: ", data.message);
        recensioniDiv.innerHTML = "<p class='errore'>Impossibile caricare le recensioni.<br><br>Controllare la connessione al database.<br> Non esiste</p>";
      }
    })
    .catch(error => {
      console.error("Errore:", error);
      recensioniDiv.innerHTML = "<p class='errore'>Impossibile caricare le recensioni.<br><br>Controllare la connessione.</p>";
    });
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
      // Mappa tra i testi dei bottoni e i nomi delle sezioni
      const buttonToSectionMap = {
          "esami": "esami",
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
    
    // Imposta per i bottoni della nav-bar
    const navButtons = document.querySelectorAll('.nav-bar .bottone');
    const navSections = {
      "esami": document.querySelector(".esami"),
      "recensioni": document.querySelector(".recensioni"),
    };
    // Imposta "esami" come default
    loadEsami();

    setupButtonGroup(navButtons, "esami", navSections);

    openRec();
  
  
  });


function inviaRecensione(event){
  event.preventDefault();
  
  Swal.fire({
    title: 'Conferma invio',
    text: "Sei sicuro di voler inviare questa recensione?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sì, invia!',
    cancelButtonText: 'Annulla'
  })
  .then(result =>{
    if(result.isConfirmed){

      const form=document.querySelector(".recensione-form");
      const formData=new FormData(form);
      const prof_id=getUrlParameter('id');
      formData.append('prof_id', prof_id);
      fetch('/src/server/carica_recensioni_prof.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          Swal.fire('Errore!', 'Errore durante l\'invio della recensione.', 'error');
        }
        return response.json(); 
      })
      .then(data => {
        if (data.success) {
          Swal.fire('Successo!', 'Recensione inviata con successo!', 'success');
          openRec();
        } else {
          Swal.fire('Errore!', data.message, 'error');
        }
      })
      .catch(error => {
        Swal.fire('Errore!', 'Errore durante l\'invio della recensione .', 'error');
      });

    }
  })
}

