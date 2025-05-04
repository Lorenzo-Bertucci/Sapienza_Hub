function getUrlParameter(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
}

document.addEventListener("DOMContentLoaded", function (){
    const dati = document.querySelector(".dati");
    const desc = document.querySelector(".desc");
    const profDiv=document.querySelector(".professori");


    const corso=getUrlParameter('corso');

    const codice=getUrlParameter('codice');


    if (!codice || !corso) {
      desc.innerHTML = "<p>Codice del corso non specificato.</p>";
      return;
    }

  function loadEsame(corso, codice) {
    fetch(`/src/server/get_info.php?corso=${encodeURIComponent(corso)}&codice=${encodeURIComponent(codice)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Errore durante il caricamento dei dati.");
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const corso = data.data;
                if (!corso.descrizione) {
                    window.location.href = "/src/client/html/404.php";
                    return;
                }
                document.title = `${corso.nome} - SapienzHub`;
                dati.innerHTML = `
                    <p><strong>CFU:</strong> ${corso.cfu}</p>
                    <p><strong>SSD:</strong> ${corso.ssd} anni</p>
                    <p><strong>Lingua:</strong> ${corso.lingua}</p>
                    <p><strong>Tipo:</strong> ${corso.tipo}</p>
                    <p><strong>Ambito:</strong> ${corso.ambito}</p>
                    <a href=${corso.link} style="color: black;" target="_blank">
                    <strong>Link del corso</strong>
                    </a>
                    <br><br>
                `;
                desc.innerHTML = `
                    <br><h1>${corso.nome}</h1>
                    <br><p>${corso.descrizione}</p>
                `;

                loadProf();


            } else {
                desc.innerHTML = `<p class='errore'>${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error("Errore:", error);
            desc.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";
        });
  }

  function loadProf(){
    const esame=getUrlParameter('codice');
    fetch(`/src/server/get_prof.php?esame=${encodeURIComponent(esame)}`)
      .then(response => {
        if (!response.ok) {
            throw new Error("Errore durante il caricamento dei professori.");
        }
        return response.json();
      })
      .then(data => {
          if (data.success) {
              const prof = data.data;
              console.log(prof); 
              if (prof.length === 0) {
                  profDiv.innerHTML = "<h3 style='text-align:center;'>Nessun professore trovato per questo corso.</h3>";
                  return;
              }
              profDiv.innerHTML = "<h2 style='text-align:center;'>Professori:</h2>"
              prof.forEach(profess =>{
                const cardprof=document.createElement('div');
                cardprof.classList.add("prof");
                cardprof.innerHTML=`
                    <a>
                        <h3>${profess.nome}</h3>
                    </a>`;
                profDiv.appendChild(cardprof);
              });
          } else {
              profDiv.innerHTML = `<p class='errore'>${data.message}</p>`;
          }
      })
      .catch(error => {
          console.error("Errore:", error);
          esamiContainer.innerHTML = "<p class='errore'>Errore durante il caricamento degli esami.</p>";
      });
  }

  loadEsame(corso, codice);

});


function openRec(){
  const recensioniDiv = document.querySelector(".php");
  const esame=getUrlParameter('codice');
  fetch(`/src/server/recensioni_esami.php?esame=${encodeURIComponent(esame)}`)
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
          rec.innerHTML+=`<div><span class='utente'><strong>~${recensione.utente}</strong></span><span style="margin-left: 10px; font-size: smaller; font-style: italic;"><small><i> ${recensione.dat}</i></small></span></div> <p>${recensione.testo}</p>`;
        });
        recensioniDiv.appendChild(rec);
      }else{
        console.error("Errore nel caricamento dati: ", data.message);
        recensioniDiv.innerHTML = "<p class='errore'>Impossibile caricare il materiale.<br><br>Controllare la connessione al database.<br> Non esiste</p>";
      }
    })
    .catch(error => {
      console.error("Errore:", error);
      recensioniDiv.innerHTML = "<p class='errore'>Impossibile caricare le recensioni.<br><br>Controllare la connessione.</p>";
    });
}

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
      const esame=getUrlParameter('codice');
      formData.append('esame', esame);
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

function openMat(){
  const materialeDiv=document.querySelector(".materiale");
  const esame=getUrlParameter('codice');
  fetch(`/src/server/materiale_didattico.php?esame=${encodeURIComponent(esame)}`)
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
        const nomedata=document.createElement('div');
        nomedata.classList.add("form-container");
        nomedata.innerHTML=`<div><span class='utente'><strong>~${didattico.utente}</strong></span>
        <span style="margin-left: 10px; font-size: smaller; font-style: italic;"><small><i> ${didattico.dat}</i></small></span></div>`;
        const link = document.createElement('a');
        link.href=`/src/server/download_materiale.php?esame=${encodeURIComponent(esame)}&nomefile=${encodeURIComponent(didattico.nomefile)}`
        link.textContent=didattico.nomefile;
        link.download=didattico.nomefile;
        link.style.display = 'block';
        nomedata.appendChild(link);
        mat.appendChild(nomedata);
        });
      }else{
        console.error("Errore nel caricamento dati: ", data.message);
        materialeDiv.innerHTML = "<p class='errore'>Impossibile caricare il materiale.<br><br>Controllare la connessione al database.<br> Non esiste</p>";
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
      const esame=getUrlParameter('codice');
      formData.append('esame', esame);
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
