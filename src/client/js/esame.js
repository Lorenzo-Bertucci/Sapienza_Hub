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

function openRec(materia){
  const recensioniDiv = document.querySelector(".php");
  fetch(`/src/server/recensioni_esami.php?materia=${encodeURIComponent(materia)}`)
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
        tit.innerHTML='<b>Dicono di questo esame:</b>';
        recensioniDiv.appendChild(tit);
        const rec=document.createElement('div');
        rec.classList.add("rec");
        data.recensioni.forEach(recensione=>{
          rec.innerHTML+=`<div><span class='utente'><strong>~${recensione.nome}</strong></span><span style="margin-left: 10px; font-size: smaller; font-style: italic;"><small><i> ${recensione.dat}</i></small></span></div> <p>${recensione.testo}</p>`;
        });
        recensioniDiv.appendChild(rec);
      }else{
        console.error("Errore nel caricamento dati: ", data.message);
      }
    })
    .catch(error => {
      console.error("Errore:", error);
      recensioniDiv.innerHTML = "<p class='errore'>Impossibile caricare le recensioni.<br><br>Controllare la connessione.</p>";
    });
}

function inviaRecensione(event, materia){
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
      formData.append('materia', materia);
      fetch('/src/server/carica_recensioni.php', {
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
          openRec(materia);
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

function openMat(materia){
  const materialeDiv=document.querySelector(".materiale");
  fetch(`/src/server/materiale_didattico.php?materia=${encodeURIComponent(materia)}`)
    .then(response => {
      console.log(response);
        if (!response.ok) {
          throw new Error("Errore nel caricamento del materiale.");
        }
        return response.json();
    })
    .then(data => {
      if(data.success){
        materialeDiv.innerHTML='';
        const tit=document.createElement('div');
        tit.classList.add("titolo");
        tit.innerHTML='<b>Materiale su questo esame:</b>';
        materialeDiv.appendChild(tit);
        const mat=document.createElement('div');
        mat.classList.add("mat");
        materialeDiv.appendChild(mat);
        data.materiale.forEach(didattico=>{
        /*  mat.innerHTML+=`<div><span class='utente'><strong>~${didattico.nome}</strong></span>
          <span style="margin-left: 10px; font-size: smaller; font-style: italic;"><small><i> ${didattico.dat}</i></small></span></div> <p>${didattico.nomefile}</p>`;
        });
        //<a href="download_materiale.php?nomefile=${encodeURIComponent(${didattico.nomefile})}"></a>
        materialeDiv.appendChild(mat);*/
        const nomedata=document.createElement('div');
        nomedata.innerHTML=`<div><span class='utente'><strong>~${didattico.nome}</strong></span>
          <span style="margin-left: 10px; font-size: smaller; font-style: italic;"><small><i> ${didattico.dat}</i></small></span></div>`;
        mat.appendChild(nomedata);
        const link = document.createElement('a');
        link.href=`/src/server/download_materiale.php?materia=${encodeURIComponent(materia)}&nomefile=${encodeURIComponent(didattico.nomefile)}`
        link.textContent=didattico.nomefile;
        link.download=didattico.nomefile;
        link.style.display = 'block';
        mat.appendChild(link);
        });
      }else{
        console.error("Errore nel caricamento dati: ", data.message);
      }
    })
    .catch(error => {
      console.error("Errore:", error);
      materialeDiv.innerHTML = "<p class='errore'>Impossibile caricare il materiale.<br><br>Controllare la connessione.</p>";
    });

}

function inviaMateriale(event, materia){
  event.preventDefault();
  
  Swal.fire({
    title: 'Conferma invio',
    text: "Sei sicuro di voler inviare questo documento?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sì, invia!',
    cancelButtonText: 'Annulla'
  })
  .then(result =>{
    if(result.isConfirmed){

      const form=document.querySelector(".materiale-form");
      const formData=new FormData(form);
      formData.append('materia', materia);
      fetch('/src/server/carica_materiale.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          Swal.fire('Errore!', 'Errore durante l\'invio del documento.', 'error');
        }
        return response.json(); 
      })
      .then(data => {
        if (data.success) {
          Swal.fire('Successo!', 'Documento inviato con successo!', 'success');
          openMat(materia);
        } else {
          Swal.fire('Errore!', data.message, 'error');
        }
      })
      .catch(error => {
        Swal.fire('Errore!', 'Errore durante l\'invio del materiale catch esame.js.', 'error');
      });


    }
  })


}