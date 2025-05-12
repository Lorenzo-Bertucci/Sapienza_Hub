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

// Funzione per creare la card di una recensione
function createRec(utente,data,testo){
  const rec=document.createElement('div');
  rec.classList.add("rec");

  data = new Date(data).toLocaleDateString("it-IT", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric"
  });
  rec.innerHTML=`<div><span class='utente'><strong>~${utente}</strong></span><span style="margin-left: 10px; font-size: smaller; font-style: italic;"><small><i> ${data}</i></small></span></div> <p>${testo}</p>`;

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
        const rec=createRec(recensione.utente,recensione.dat,recensione.testo);
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

// Funzione per creare la card di un materiale didattico
function createMat(esame,utente,data,nomefile){
  const nomedata=document.createElement('div');
  nomedata.classList.add("materiale-card");
  nomedata.innerHTML=`<div><span class='utente'><strong>~${utente}</strong></span>
  <span style="margin-left: 10px; font-size: smaller; font-style: italic;"><small><i> ${data}</i></small></span></div>`;
  const link = document.createElement('a');
  link.href=`/src/server/download_materiale.php?esame=${encodeURIComponent(esame)}&nomefile=${encodeURIComponent(nomefile)}`
  link.textContent=nomefile;
  link.download=nomefile;
  link.style.display = 'block';
  nomedata.appendChild(link);

  return nomedata;
}

// Funzione per caricare il materiale didattico
function openMat(){
  const materialeContainer=document.querySelector(".materiale");
  const esame=getUrlParameter('codice');

  fetch(`/src/server/esame/get_materiale.php?esame=${encodeURIComponent(esame)}`)
  .then(response => {
    if (!response.ok) {
      throw new Error("Errore nel caricamento del materiale.");
    }
    return response.json();
  })
  .then(data => {
    if(data.success){
      materialeContainer.innerHTML='';
      const mat=document.createElement('div');
      mat.classList.add("mat");
      const titolo=document.createElement('div');
      titolo.classList.add("titolo");
      titolo.innerHTML='<b>Materiale su questo esame</b>';
      mat.appendChild(titolo);
      materialeContainer.appendChild(mat);

      data.materiale.forEach(didattico=>{
        const card=createMat(esame,didattico.utente,didattico.dat,didattico.nomefile);
        mat.appendChild(card);
      });
    }else{
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
      document.titolo = `${esame.nome} - SapienzHub`;
      dati.innerHTML = `
        <p><strong>CFU:</strong> ${esame.cfu}</p>
        <p><strong>SSD:</strong> ${esame.ssd}</p>
        <p><strong>Lingua:</strong> ${esame.lingua}</p>
        <p><strong>Tipo:</strong> ${esame.tipo}</p>
        <p><strong>Ambito:</strong> ${esame.ambito}</p>
        <a href=${esame.link} style="color: black;" target="_blank">
          <strong>Link del corso</strong>
        </a>
        <br><br>
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

// Funzione per inviare una recensione
function inviaRecensione(event, id){
  event.preventDefault();
  
  Swal.fire({
    titolo: 'Conferma invio',
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
      const esame=getUrlParameter('codice');
      formData.append('esame', esame);
      formData.append('id', id);
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
function inviaMateriale(event, id){
  event.preventDefault();
  
  Swal.fire({
    titolo: 'Conferma invio',
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
      const esame=getUrlParameter('codice');
      formData.append('esame', esame);
      formData.append('id', id);
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

// Inizializza la pagina
document.addEventListener("DOMContentLoaded", function (){

  const codice=getUrlParameter('codice');

  if (!codice) {
    window.location.href = "/src/client/html/404.php";
    return;
  }
  //Controlla se il corso è già nei preferiti
  checkFavorite(codice);

  loadEsame(codice);
  openRec();
  openMat();
});

