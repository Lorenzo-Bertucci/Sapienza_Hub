// Funzione per ottenere un parametro dall'URL
function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// Funzione per segnalare una recensione
function reportReview(reviewId) {
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
      
      fetch('/src/server/php/home_prof/report_review.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Segnalata!', data.message, 'success');
          // Ricarica le recensioni dopo la segnalazione
          openRec();
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

// Funzione per creare il pulsante per eliminare una recensione
function createDeleteButtonRecensione(id){
    const deleteButton = document.createElement("button");
    deleteButton.innerHTML = `<i class="fa fa-trash" aria-hidden="true" style="font-size: 22px;"></i>`;
    deleteButton.classList.add("delete-button");

    deleteButton.addEventListener("click", (event) => {
        event.stopPropagation(); // Impedisce il click sulla card

        Swal.fire({
            title: "Sei sicuro di voler eliminare la recensione?",
            text: "Questa azione non può essere annullata",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "rgb(170, 33, 33)",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sì, elimina",
            cancelButtonText: "Annulla"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/src/server/php/home_prof/delete_recensione.php?id=${encodeURIComponent(id)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Errore durante l'eliminazione della recensione.");
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire("Eliminata!", "La recensione è stata eliminata", "success")
                    .then(() => {
                        openRec(); // Ricarica le recensioni
                    });
                    }else {
                        Swal.fire("Errore", data.message, "error");
                    }
                })
                .catch(error => {
                    Swal.fire("Errore", "Si è verificato un errore durante l'eliminazione", "error");
                    console.error("Errore:", error);
                });
            }
        });
    });

    return deleteButton;
}

// Funzione per creare una card per una recensione
function createRec(id,utente,data,testo,foto,id_utente){
  const rec=document.createElement('div');
  rec.classList.add("rec");
  data = new Date(data).toLocaleDateString("it-IT", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric"
  });
  const profileImage = foto && foto.trim() !== "" ? foto : '/src/client/assets/utente.png';
  // Recupera l'user_id dalla sessione (localStorage)
  let currentUserId = null;
  try {
    currentUserId = localStorage.getItem('user_id');
  } catch (e) {
    currentUserId = null;
  }

  const profileLink = `/src/client/html/utente.php?id=${encodeURIComponent(id_utente)}`;
  const dashboardLink = `/src/client/html/dashboard.php`;

  if (currentUserId && id_utente == currentUserId) {
      rec.innerHTML = `
      <div style="display:flex; align-items:center;">
          <a href="${dashboardLink}" style="display:flex; align-items:center; text-decoration:none; color:inherit; padding:0px;">
              <img src="${profileImage}" alt="Profilo di ${utente}" style="width:40px; height:40px; border-radius:50%; margin-right:10px;">
              <span class='utente'><strong>${utente}</strong></span>
          </a>
          <span style="margin-left: 10px; font-size: smaller; font-style: italic;">
              <small><i>${data}</i></small>
          </span>
      </div>
      <p>${testo}</p>
  `;
  const deleteButton = createDeleteButtonRecensione(id);
  rec.appendChild(deleteButton);
  }
  else{
      rec.innerHTML = `
      <div style="display:flex; align-items:center;">
          <a href="${profileLink}" style="display:flex; align-items:center; text-decoration:none; color:inherit; padding:0px;">
              <img src="${profileImage}" alt="Profilo di ${utente}" style="width:40px; height:40px; border-radius:50%; margin-right:10px;">
              <span class='utente'><strong>${utente}</strong></span>
          </a>
          <span style="margin-left: 10px; font-size: smaller; font-style: italic;">
              <small><i>${data}</i></small>
          </span>
          <button class="report-btn" title="Segnala questa recensione" onclick="reportReview(${id})">
              <i class="fa fa-exclamation-triangle"></i> Segnala
          </button>
      </div>
      <p>${testo}</p>
  `;
  }

  return rec;
}

// Funzione per caricare le recensioni
function openRec(){
  const recensioniContainer = document.querySelector(".rec-esami");
  const prof_id=getUrlParameter('id');
  fetch(`/src/server/php/home_prof/get_recensioni.php?id=${encodeURIComponent(prof_id)}`)
  .then(response => {
    if (!response.ok) {
      throw new Error("Errore nel caricamento delle recensioni.");
    }
    return response.json();
  })
  .then(data => {
    if(data.success){
      recensioniContainer.innerHTML='';
      const titolo=document.createElement('div');
      titolo.classList.add("titolo");
      titolo.innerHTML='<b>Dicono di questo Professore</b>';
      recensioniContainer.appendChild(titolo);

      data.recensioni.forEach(recensione=>{
        const rec=createRec(recensione.id,recensione.utente,recensione.dat,recensione.testo,recensione.foto,recensione.id_utente);
        recensioniContainer.appendChild(rec);
      });
    }else{
      console.error("Errore nel caricamento dati: ", data.message);
      recensioniContainer.innerHTML = "<p class='errore'>Impossibile caricare le recensioni.<br><br>Controllare la connessione al database.<br> Non esiste</p>";
    }
  })
  .catch(error => {
    console.error("Errore:", error);
    recensioniContainer.innerHTML = "<p class='errore'>Impossibile caricare le recensioni.<br><br>Controllare la connessione.</p>";
  });
}

// Funzione per caricare le informazioni del professore
function getInfo(){
  const infoDiv=document.querySelector(".desc");
  const prof_id=getUrlParameter('id');
  fetch(`/src/server/php/home_prof/get_dati_prof.php?id=${encodeURIComponent(prof_id)}`)
  .then(response => {
        if (!response.ok) {
          throw new Error("Errore nel caricamento delle recensioni.");
        }
        return response.json();
    })
  .then(data => {
    if(data.success){
      const professore=data.info;
      if (!professore.email) {
          window.location.href = "/src/client/html/404.php";
          return;
      }
      document.title = `${professore.nome} - SapienzHub`;
      infoDiv.innerHTML=`
        <h1>${professore.nome}</h1>
        <p>
          <strong>E-mail</strong>: <a href='mailto:${professore.email}' style='color: black;'>${professore.email}</a>
          <br>
          <strong>Dipartimento</strong>: ${professore.dip}
          <br>
          <strong>SSD</strong>: ${professore.ssd}
          <br>
          <a href='${professore.link}' style='color: black;' target='_blank'>
              <strong>Link sito</strong>
          </a>
        </p>`;

      const fotoImg = document.querySelector('.foto img');
      fotoImg.src = (professore.foto && professore.foto.trim() !== "") ? professore.foto : 'assets/utente.png';

    }else{
      console.error("Errore nel caricamento dati: ", data.message);
      recensioniContainer.innerHTML = "<p class='errore'>Impossibile caricare le recensioni.<br><br>Controllare la connessione al database.<br> Non esiste</p>";
    }
  })
  .catch(error => {
    console.error("Errore:", error);
    recensioniContainer.innerHTML = "<p class='errore'>Impossibile caricare le recensioni.<br><br>Controllare la connessione.</p>";
  });
}

// Funzione per creare una card per l'esame
function createEsame(corso,codice,nome){
  const card=document.createElement('div');
  card.classList.add("card");
  card.innerHTML=`
    <a href="/src/client/html/esame.php?corso=${corso}&codice=${codice}">
        <h3>${nome}</h3>
    </a>`
  ;

  return card;
}

// Funzione per caricare gli esami del professore
function loadEsami(){
  const id=getUrlParameter('id');
  const divEsame = document.querySelector(".esami");

  fetch(`/src/server/php/home_prof/get_esami.php?id=${encodeURIComponent(id)}`)
  .then(response => {
    if (!response.ok) {
      throw new Error("Errore durante il caricamento degli esami.");
    }
    return response.json();
  })
  .then(data => {
    if (data.success) {
      const esami = data.data;

      esami.forEach(esame =>{
        const card=createEsame(esame.corso,esame.codice,esame.nome);
        divEsame.appendChild(card);
      });
    }
    else{
      console.error(data.message);
      divEsame.innerHTML = `<h3 class='errore'>${data.message}</h3>`;
    }    
  })
  .catch(error => {
    console.error("Errore:", error);
    divEsame.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";
  });
}

// Funzione per gestire il gruppo di bottoni e le sezioni
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

// Funzione per inviare la recensione dopo il controllo delle parole proibite
function inviaRecensione(event) {
  event.preventDefault();
  
  // Recupera il testo della recensione (assicurati che il textarea abbia name="recensione")
  const recensioneInput = document.querySelector(".recensione-form textarea[name='testo']");
  const reviewText = recensioneInput.value.toLowerCase();
  
  // Lista di parole proibite
  const forbiddenWords = [
    "allupato",
    "ammucchiata",
    "anale",
    "arrapato",
    "arrusa",
    "arruso",
    "assatanato",
    "bagascia",
    "bagassa",
    "bagnarsi",
    "baldracca",
    "balle",
    "battere",
    "battona",
    "belino",
    "biga",
    "bocchinara",
    "bocchino",
    "bofilo",
    "boiata",
    "bordello",
    "brinca",
    "bucaiolo",
    "budiùlo",
    "busone",
    "cacca",
    "caciocappella",
    "cadavere",
    "cagare",
    "cagata",
    "cagna",
    "casci",
    "cazzata",
    "cazzimma",
    "cazzo",
    "cesso",
    "cazzone",
    "checca",
    "chiappa",
    "chiavare",
    "chiavata",
    "ciospo",
    "ciucciami il cazzo",
    "coglione",
    "coglioni",
    "cornuto",
    "cozza",
    "culattina",
    "culattone",
    "culo",
    "ditalino",
    "fava",
    "femminuccia",
    "fica",
    "figa",
    "figlio di buona donna",
    "figlio di puttana",
    "figone",
    "finocchio",
    "fottere",
    "fottersi",
    "fracicone",
    "fregna",
    "frocio",
    "froscio",
    "goldone",
    "guardone",
    "imbecille",
    "incazzarsi",
    "incoglionirsi",
    "ingoio",
    "leccaculo",
    "lecchino",
    "lofare",
    "loffa",
    "loffare",
    "mannaggia",
    "merda",
    "merdata",
    "merdoso",
    "mignotta",
    "minchia",
    "minchione",
    "mona",
    "monta",
    "montare",
    "mussa",
    "nave scuola",
    "nerchia",
    "padulo",
    "palle",
    "palloso",
    "patacca",
    "patonza",
    "pecorina",
    "pesce",
    "picio",
    "pincare",
    "pippa",
    "pinnolone",
    "pipì",
    "pippone",
    "pirla",
    "pisciare",
    "piscio",
    "pisello",
    "pistolotto",
    "pomiciare",
    "pompa",
    "pompino",
    "porca",
    "porca madonna",
    "porca miseria",
    "porca puttana",
    "porco",
    "porco due",
    "porco zio",
    "potta",
    "puppami",
    "puttana",
    "quaglia",
    "recchione",
    "regina",
    "rincoglionire",
    "rizzarsi",
    "rompiballe",
    "rompipalle",
    "ruffiano",
    "sbattere",
    "sbattersi",
    "sborra",
    "sborrata",
    "sborrone",
    "sbrodolata",
    "scopare",
    "scopata",
    "scorreggiare",
    "sega",
    "slinguare",
    "slinguata",
    "smandrappata",
    "soccia",
    "socmel",
    "sorca",
    "spagnola",
    "spompinare",
    "sticchio",
    "stronza",
    "stronzata",
    "stronzo",
    "succhiami",
    "succhione",
    "sveltina",
    "sverginare",
    "tarzanello",
    "terrone",
    "testa di cazzo",
    "tette",
    "tirare",
    "topa",
    "troia",
    "trombare",
    "vacca",
    "vaffanculo",
    "vangare",
    "zinne",
    "zio cantante",
    "zoccola"
  ];
  
  // Verifica se il testo contiene una delle parole proibite
  const containsForbidden = forbiddenWords.some(word => reviewText.includes(word));
  if (containsForbidden) {
    Swal.fire({
      icon: 'error',
      title: 'Errore nella recensione',
      text: 'La recensione contiene parole non consentite',
      confirmButtonColor: 'rgb(170, 33, 33)'
    });
    return;
  }
  
  // Se il controllo va a buon fine, chiedi conferma all'utente
  Swal.fire({
    title: 'Conferma invio',
    text: "Sei sicuro di voler inviare questa recensione?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: 'rgb(170, 33, 33)',
    confirmButtonText: 'Sì, invia!',
    cancelButtonText: 'Annulla'
  }).then(result => {
    if (result.isConfirmed) {
      const form = document.querySelector(".recensione-form");
      const formData = new FormData(form);
      const prof_id = getUrlParameter('id');
      formData.append('prof_id', prof_id);
  
      fetch('/src/server/php/home_prof/carica_recensione.php', {
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
        Swal.fire('Errore!', 'Errore durante l\'invio della recensione.', 'error');
      });
    }
  });
}

// Funzione principale per inizializzare la pagina
document.addEventListener("DOMContentLoaded", function() {    
  // Imposta per i bottoni della nav-bar
  const navButtons = document.querySelectorAll('.nav-bar .bottone');
  const navSections = {
    "esami": document.querySelector(".esami"),
    "recensioni": document.querySelector(".recensioni"),
  };

  setupButtonGroup(navButtons, "esami", navSections);
  getInfo();
  loadEsami();
  openRec();
});