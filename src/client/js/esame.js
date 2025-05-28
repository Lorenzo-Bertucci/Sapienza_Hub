// Funzione per recuperare un parametro dall'URL
function getUrlParameter(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
}

// Funzione per aggiornare lo stato del pulsante dei preferiti
function updateFavoriteState(isFavorite) {
  const favoriteBtn = document.querySelector(".favorite-btn");
  if (isFavorite) {
    favoriteBtn.classList.add("active");
    favoriteBtn.titolole = "Rimuovi dai preferiti";
  } else {
    favoriteBtn.classList.remove("active");
    favoriteBtn.titolole = "Aggiungi ai preferiti";
  }
}

// Funzione per gestire il click sul pulsante dei preferiti
function favorite() {
  const favoriteBtn = document.querySelector(".favorite-btn");
  const action = favoriteBtn.classList.contains("active") ? "remove" : "add";
  const esameCodice = getUrlParameter("codice"); // Recupera il codice del corso dall'URL
  const esameNome=document.getElementById("corso-nome").textContent

  fetch(`/src/server/esame/toggle_favorite.php`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({codice:esameCodice,corso:esameNome,action:action}),
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
    updateFavoriteState(action === "add");
    } else {
      alert(data.message);
    }
  })
  .catch(error => {
    console.error("Errore:", error);
    alert("Si è verificato un errore.");
  });
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
      
      fetch('/src/server/esame/report_review.php', {
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

// Funzione per creare la card di una recensione
function createRec(id,utente,data,testo){
  const rec=document.createElement('div');
  rec.classList.add("rec");

  data = new Date(data).toLocaleDateString("it-IT", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric"
  });

  rec.innerHTML = `
      <div>
        <span class='utente'><strong>~${utente}</strong></span>
        <span style="margin-left: 10px; font-size: smaller; font-style: italic;">
          <small><i>${data}</i></small>
        </span>
        <button class="report-btn" title="Segnala questa recensione" onclick="reportReview(${id})">
          <i class="fa fa-exclamation-triangle"></i> Segnala
        </button>
      </div>
      <p>${testo}</p>
    `;
    return rec;
  
}

// Funzione per caricare le recensioni
function openRec(){
  const recensioniContainer = document.querySelector(".rec-esami");
  const esame=getUrlParameter('codice');
  fetch(`/src/server/esame/get_recensioni.php?esame=${encodeURIComponent(esame)}`)
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
      titolo.innerHTML='<b>Dicono di questo esame</b>';
      recensioniContainer.appendChild(titolo);

      data.recensioni.forEach(recensione=>{
        const rec=createRec(recensione.id,recensione.utente,recensione.dat,recensione.testo);
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

      fetch('/src/server/esame/report_materiale.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Segnalato!', data.message, 'success');
          openMat();
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

// Funzione per creare la card di un materiale didattico
function createMat(esame, utente, data, nomefile, id) {
  const nomedata = document.createElement('div');
  nomedata.classList.add("materiale-card");
  nomedata.innerHTML = `<div>
    <span class='utente'><strong>~${utente}</strong></span>
    <span style="margin-left: 10px; font-size: smaller; font-style: italic;">
      <small><i> ${data}</i></small>
    </span>
    <button class="report-btn" title="Segnala questo materiale" onclick="reportMaterial(${id})">
      <i class="fa fa-exclamation-triangle"></i> Segnala
    </button>
  </div>`;
  const link = document.createElement('a');
  link.href = `/src/server/esame/download_materiale.php?esame=${encodeURIComponent(esame)}&nomefile=${encodeURIComponent(nomefile)}`;
  link.textContent = nomefile;
  link.style.display = 'block';
  link.target = '_blank';
  nomedata.appendChild(link);

  return nomedata;
}

// Funzione per caricare il materiale didattico
function openMat() {
  const materialeContainer = document.querySelector(".materiale");
  const esame = getUrlParameter('codice');

  fetch(`/src/server/esame/get_materiale.php?esame=${encodeURIComponent(esame)}`)
    .then(response => {
      if (!response.ok) {
        throw new Error("Errore nel caricamento del materiale.");
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        materialeContainer.innerHTML = '';
        const mat = document.createElement('div');
        mat.classList.add("mat");
        const titolo = document.createElement('div');
        titolo.classList.add("titolo");
        titolo.innerHTML = '<b>Materiale su questo esame</b>';
        mat.appendChild(titolo);
        materialeContainer.appendChild(mat);

        data.materiale.forEach(didattico => {
          // Assicurati che l'oggetto abbia un id unico per il materiale
          const card = createMat(esame, didattico.utente, didattico.dat, didattico.nomefile, didattico.id);
          mat.appendChild(card);
        });
      } else {
        console.error("Errore nel caricamento dati: ", data.message);
        materialeContainer.innerHTML = "<p class='errore'>Impossibile caricare il materiale.<br><br>Controllare la connessione al database.<br> Non esiste</p>";
      }
    })
    .catch(error => {
      console.error("Errore:", error);
      materialeContainer.innerHTML = "<p class='errore'>Impossibile caricare il materiale.<br><br>Controllare la connessione.</p>";
    });
}

//Funzione per caricare i dati dell'esame
function loadEsame(codice) {
  const dati = document.querySelector(".dati");

  fetch(`/src/server/esame/get_esame.php?codice=${encodeURIComponent(codice)}`)
  .then(response => {
    if (!response.ok) {
      throw new Error("Errore durante il caricamento dei dati.");
    }
    return response.json();
  })
  .then(data => {
    if (data.success) {
      const esame = data.esame;
      if (!esame.descrizione) {
        window.location.href = "/src/client/html/404.php";
        return;
      }
      document.title = `${esame.nome} - SapienzHub`;
      dati.innerHTML = `
        <p><strong>CFU:</strong> ${esame.cfu}</p>
        <p><strong>SSD:</strong> ${esame.ssd}</p>
        <p><strong>Lingua:</strong> ${esame.lingua}</p>
        <p><strong>Tipo:</strong> ${esame.tipo}</p>
        <p><strong>Ambito:</strong> ${esame.ambito}</p>
        <a href=${esame.link} style="color: black;" target="_blank">
          <strong>Link del corso</strong>
        </a>
        <br>
      `;
      document.getElementById("corso-nome").textContent = esame.nome;
      document.getElementById("corso-descrizione").textContent = esame.descrizione;

      loadProf();
    } else {
      console.error("Errore:", data.message);
      if(data.message === "Esame non trovato.") {
        window.location.href = "/src/client/html/404.php";
        return;
      }
      dati.innerHTML = `<p class='errore'>${data.message}</p>`;
    }
  })
  .catch(error => {
    console.error("Errore:", error);
    dati.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";
  });
}

// Funzione per creare la card di un professore
function createProf(id,nome){
  const cardprof=document.createElement('div');
  cardprof.classList.add("prof");
  cardprof.innerHTML=`
    <a href="/src/client/html/home_prof.php?id=${id}">
      <h3>${nome}</h3>
    </a>`;
  
  return cardprof;
}

// Funzione per caricare i professori
function loadProf(){
  const profContainer=document.querySelector(".professori");

  const esame=getUrlParameter('codice');
  fetch(`/src/server/esame/get_professori.php?esame=${encodeURIComponent(esame)}`)
  .then(response => {
    if (!response.ok) {
      throw new Error("Errore durante il caricamento dei professori.");
    }
    return response.json();
  })
  .then(data => {
    if (data.success) {
      const professori = data.professori;
      if (professori.length === 0) {
        profContainer.innerHTML = "<h3 style='text-align:center;'>Nessun professore trovato per questo corso.</h3>";
        return;
      }
      
      professori.forEach(prof =>{
      const card= createProf(prof.id_professore,prof.nome_professore);
      profContainer.appendChild(card);
      });
    } else {
      profContainer.innerHTML = `<p class='errore'>${data.message}</p>`;
    }
  })
  .catch(error => {
    console.error("Errore:", error);
    profContainer.innerHTML = "<p class='errore'>Errore durante il caricamento dei professori.</p>";
  });
}

// Funzione per controllare se l'esame è nei preferiti
function checkFavorite(codice){
  fetch(`/src/server/esame/check_favorite.php?corso=${encodeURIComponent(codice)}`)
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      updateFavoriteState(data.isFavorite);
    }
  }); 
}

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

// Funzione per inviare una recensione
function inviaRecensione(event){
  event.preventDefault();

  // Recupera il testo della recensione (assicurati che il textarea abbia name="recensione")
  const recensioneInput = document.querySelector(".recensione-form textarea[name='testo']");
  const reviewText = recensioneInput.value.toLowerCase();
  
  // Lista di parole proibite
  
  
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
  
  Swal.fire({
    title: 'Conferma invio',
    text: "Sei sicuro di voler inviare questa recensione?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: 'rgb(170, 33, 33)',
    confirmButtonText: 'Sì, invia!',
    cancelButtonText: 'Annulla'
  })
  .then(result =>{
    if(result.isConfirmed){

      const form=document.querySelector(".recensione-form");
      const formData=new FormData(form);
      const esame=getUrlParameter('codice');
      formData.append('esame', esame);
      fetch('/src/server/esame/carica_recensione.php', {
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

// Funzione per caricare un materiale 
function inviaMateriale(event){
  event.preventDefault();
  const materialeInput = document.querySelector(".materiale-form input[name='nomefile']");
  const materialeName = materialeInput.value.toLowerCase();

  const containsForbidden = forbiddenWords.some(word => materialeName.includes(word));
  if (containsForbidden) {
    Swal.fire({
      icon: 'error',
      title: 'Errore nel nome del materiale',
      text: 'Il nome del materiale contiene parole non consentite',
      confirmButtonColor: 'rgb(170, 33, 33)'
    });
    return;
  }
  
  Swal.fire({
    titolo: 'Conferma invio',
    text: "Sei sicuro di voler inviare questo documento?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: 'rgb(170, 33, 33)',
    confirmButtonText: 'Sì, invia!',
    cancelButtonText: 'Annulla'
  })
  .then(result =>{
    if(result.isConfirmed){

      const form=document.querySelector(".materiale-form");
      const formData=new FormData(form);
      const esame=getUrlParameter('codice');
      formData.append('esame', esame);
      fetch('/src/server/esame/carica_materiale.php', {
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
          openMat();
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

function setupButtonGroup(buttons, defaultString, sections) {
  // Mappa tra i testi dei bottoni e i nomi delle sezioni
  const buttonToSectionMap = {
      "professori": "professori",
      "materiale didattico": "mat-did",
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
    const btnText = btn.textContent.toLowerCase();
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

// Inizializza la pagina
document.addEventListener("DOMContentLoaded", function (){

  const codice=getUrlParameter('codice');

  if (!codice) {
    window.location.href = "/src/client/html/404.php";
    return;
  }

  // Imposta per i bottoni della barra
  const navButtons = document.querySelectorAll('.barra .bottone');
  const navSections = {
      "professori": document.querySelector(".professori"),
      "mat-did": document.querySelector(".mat-did"),
      "recensioni": document.querySelector(".recensioni")
  };

  // Imposta "professori" come default, ma controlla l'hash nell'URL
  setupButtonGroup(navButtons, "professori", navSections);
  
  //Controlla se il corso è già nei preferiti
  checkFavorite(codice);

  loadEsame(codice);
  openRec();
  openMat();
});

