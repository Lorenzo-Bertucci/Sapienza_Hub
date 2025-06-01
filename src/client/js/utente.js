// Funzione per ottenere un parametro dall'URL
function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// Funzione per segnalare una recensione
function reportReview(reviewId,tipo) {
  Swal.fire({
    title: 'Segnala recensione',
    text: "Vuoi segnalare questa recensione?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Segnala',
    cancelButtonText: 'Annulla',
    confirmButtonColor: 'rgb(170, 33, 33)', // rosso come da altri Swal
    cancelButtonColor: '#3085d6'
  }).then((result) => {
    if (result.isConfirmed) {
      const formData = new FormData();
      formData.append('review_id', reviewId);
      formData.append('tipo', tipo); // Aggiungi il tipo di recensione (esami o professori)
      
      fetch('/src/server/php/utente/report_review.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Segnalata!', data.message, 'success');
          // Ricarica le recensioni dopo la segnalazione
          loadRecensioni();
        } else {
          Swal.fire('Errore', data.message, 'error');
        }
      })
      .catch(error => {
        console.error('Errore:', error);
        Swal.fire('Errore', "Impossibile segnalare la recensione.", 'error');
      });
    }
  });
}

// Funzione per segnalare un materiale didattico
function reportMaterial(materialId) {
  Swal.fire({
    title: 'Segnala materiale',
    text: "Vuoi segnalare questo materiale?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Segnala',
    cancelButtonText: 'Annulla',
    confirmButtonColor: 'rgb(170, 33, 33)',
    cancelButtonColor: '#3085d6'
  }).then((result) => {
    if (result.isConfirmed) {
      const formData = new FormData();
      formData.append('material_id', materialId);

      fetch('/src/server/php/utente/report_materiale.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Segnalato!', data.message, 'success');
          loadFile();
        } else {
          Swal.fire('Errore', data.message, 'error');
        }
      })
      .catch(error => {
        console.error('Errore:', error);
        Swal.fire('Errore', "Impossibile segnalare il materiale.", 'error');
      });
    }
  });
}

// Funzione per creare una card per una recensione
function createRecensione(id,codice,nome,data,testo,tipo){
  const rec=document.createElement('div');
  rec.classList.add("rec");
  data = new Date(data).toLocaleDateString("it-IT", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric"
  });

  if(tipo === "esami"){
    rec.addEventListener("click", () => {
    window.location.href = `/src/client/html/esame.php?codice=${encodeURIComponent(codice)}#recensioni`;
    });
  }
  else if(tipo === "professori"){
    rec.addEventListener("click", () => {
    window.location.href = `/src/client/html/home_prof.php?id=${encodeURIComponent(codice)}#recensioni`;
    });
  }


  rec.innerHTML = `
    <div style="display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 16px;">
      <div style="display: flex; flex-direction: column; flex: 1 1 250px; min-width: 0;">
        <div style="display: flex; flex-wrap: wrap; align-items: baseline; min-width: 0;">
          <span style="font-weight: bold; font-size: 1.15em; white-space: normal; overflow-wrap: anywhere; flex-shrink: 1;">${nome}</span>
          <span style="margin-left: 1em; color: #666; font-size: 1em; white-space: nowrap;">${data}</span>
        </div>
      </div>
      <button class="report-btn" title="Segnala questa recensione" onclick="event.stopPropagation(); reportReview(${id},'${tipo}')" style="flex-shrink: 0; margin-left: 0;">
        <i class="fa fa-exclamation-triangle"></i> Segnala
      </button>
    </div>
    <p>${testo}</p>
  `;
  
  return rec;
}

// Funzione per caricare le recensioni
function loadRecensioni() {
    const recesamiContainer = document.querySelector(".rec-esami");
    const recprofessoriContainer = document.querySelector(".rec-professori");
    const id = getUrlParameter('id');


    fetch(`/src/server/php/utente/get_recensioni.php?tipo=esami&id=${encodeURIComponent(id)}`)
    .then(response => { 
        if (!response.ok) {
            throw new Error("Errore durante il caricamento delle recensioni degli esami");
        }
        return response.json();
        })
    .then(data => {
        if (data.success) {
            recesamiContainer.innerHTML = "<h3><b>Esami</b></h3>"; // Pulisci il contenitore prima di aggiungere le recensioni
            if (data.recensioni.length === 0) {
            const noRecensioniMessage = document.createElement("p");
            noRecensioniMessage.textContent = "Non ha ancora lasciato nessuna recensione su un esame";
            recesamiContainer.appendChild(noRecensioniMessage);
            }

            // Aggiungi le card delle recensioni
            data.recensioni.forEach(recensione => {
            const card = createRecensione(recensione.id,recensione.codice,recensione.nome,recensione.dat,recensione.testo,"esami");
            recesamiContainer.appendChild(card);
            });
        } else {
            console.error(data.message);
        }
    })
    .catch(error => {
        console.error("Errore:", error);
    });

    fetch(`/src/server/php/utente/get_recensioni.php?tipo=professori&id=${encodeURIComponent(id)}`)
    .then(response => { 
        if (!response.ok) {
            throw new Error("Errore durante il caricamento delle recensioni");
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            recprofessoriContainer.innerHTML = "<h3><b>Professori</b></h3>"; // Pulisci il contenitore prima di aggiungere le recensioni
            if (data.recensioni.length === 0) {
                const noRecensioniMessage = document.createElement("p");
                noRecensioniMessage.textContent = "Non ha ancora lasciato nessuna recensione su un professore";
                recprofessoriContainer.appendChild(noRecensioniMessage);
            }

            // Aggiungi le card dei corsi
            data.recensioni.forEach(recensione => {
                const card = createRecensione(recensione.id,recensione.id_professore,recensione.nome,recensione.dat,recensione.testo,"professori");
                recprofessoriContainer.appendChild(card);
            });
        } else {
            console.error(data.message);
        }
    })
    .catch(error => {
        console.error("Errore:", error);
    });
}

// Funzione per caricare le informazioni del professore
function getInfo(){
  const infoDiv=document.querySelector(".info");
  const prof_id=getUrlParameter('id');
  fetch(`/src/server/php/utente/get_info.php?id=${encodeURIComponent(prof_id)}`)
  .then(response => {
        if (!response.ok) {
          throw new Error("Errore nel caricamento delle recensioni.");
        }
        return response.json();
    })
  .then(data => {
    if(data.success){
      const utente=data.info;

      document.title = `${utente.nome} - SapienzHub`;
      infoDiv.innerHTML = `
        <h1>${utente.nome}</h1>
        <p><strong>•Data di registrazione:</strong> ${utente.data_registrazione ? new Date(utente.data_registrazione).toLocaleDateString("it-IT") : 'Non disponibile'}</p>
        <p><strong>•Corso di laurea:</strong> ${utente.corso || 'Non disponibile'}</p>    
        `;

      const fotoImg = document.querySelector('.foto img');
      fotoImg.src = (utente.foto && utente.foto.trim() !== "") ? utente.foto : 'assets/utente.png';

    }else{
      console.error("Errore nel caricamento dati: ", data.message);
      infoDiv.innerHTML = "<p class='errore'>Impossibile caricare le informazioni.<br><br>Controllare la connessione al database.<br> Non esiste</p>";
    }
  })
  .catch(error => {
    console.error("Errore:", error);
    infoDiv.innerHTML = "<p class='errore'>Impossibile caricare le informazioni.<br><br>Controllare la connessione.</p>";
  });
}

// Funzione per creare una card per un file
function createFile(id,codice,nome_esame,nome_file,dat) {
    const card = document.createElement("div");
    card.classList.add("mat");
    const formattedDate = new Date(dat).toLocaleDateString("it-IT", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric"
    });

    card.addEventListener("click", () => {
        window.location.href = `/src/client/html/esame.php?codice=${encodeURIComponent(codice)}#mat-did`;
    });
    card.innerHTML = `
      
        <h2><b>${nome_esame}</b>&emsp;&emsp;${formattedDate}</h2>
        <p>${nome_file}</p>
        <button class="report-btn" title="Segnala questo materiale" onclick="event.stopPropagation(); reportMaterial(${id})">
              <i class="fa fa-exclamation-triangle"></i> Segnala
        </button>
      
    `;
    return card;
}

// Funzione per caricare i file dal database
function loadFile() {
    const filesContainer = document.querySelector(".file");
    const id = getUrlParameter('id');
    filesContainer.innerHTML = ""; // Pulisci il container

    fetch(`/src/server/php/utente/get_file.php?id=${encodeURIComponent(id)}`)
    .then(response => { 
      if (!response.ok) {
        throw new Error("Errore durante il caricamento dei file");
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        if (data.files.length === 0) {
            const noFileMessage = document.createElement("h3");
            noFileMessage.textContent = "Non ha ancora condiviso nessun file";
            
            filesContainer.style.display = "flex";
            filesContainer.style.marginTop = "2em";
            filesContainer.style.flexDirection = "column";
            filesContainer.style.justifyContent = "center";
            filesContainer.style.alignItems = "center";
            filesContainer.appendChild(noFileMessage);
        }

        // Aggiungi le card dei file
        data.files.forEach(file => {
          const card = createFile(file.id, file.codice, file.nome, file.nomefile, file.dat);
          filesContainer.appendChild(card);
        });
      } else {
        console.error(data.message);
      }
    })
    .catch(error => {
      console.error("Errore:", error);
    });
}

// Funzione principale per inizializzare la pagina
document.addEventListener("DOMContentLoaded", function() {    

  getInfo();
  loadRecensioni();
  loadFile();
});