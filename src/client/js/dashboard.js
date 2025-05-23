// Funzione per creare una card corso
function createCorso(codice,nome) {
    const card = document.createElement("div");
    card.classList.add("card");
    card.innerHTML = `
        <a href="/src/client/html/laurea.php?corso=${encodeURIComponent(codice)}">
            <h3>${nome}</h3>
        </a>
    `;
    return card;
}

// Funzione per creare una card esame
function createEsame(codice,nome) {
    const card = document.createElement("div");
    card.classList.add("card");
    card.innerHTML = `
        <a href="/src/client/html/esame.php?codice=${encodeURIComponent(codice)}">
            <h3>${nome}</h3>
        </a>
    `;
    return card;
}

// Funzione per creare il pulsante per eliminare una recensione
function createDeleteButtonRecensione(id,tipo){
    const deleteButton = document.createElement("button");
    deleteButton.textContent = "×";
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
                fetch(`/src/server/dashboard/delete_recensione.php?id=${encodeURIComponent(id)}&tipo=${encodeURIComponent(tipo)}`)
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
                        loadRecensioni(); // Ricarica le recensioni
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

// Funzione per creare il pulsante per eliminare un file
function createDeleteButtonFile(id){
    const deleteButton = document.createElement("button");
    deleteButton.textContent = "×";
    deleteButton.classList.add("delete-button");

    deleteButton.addEventListener("click", (event) => {
        event.stopPropagation(); // Impedisce il click sulla card

        Swal.fire({
            title: "Sei sicuro di voler eliminare il file?",
            text: "Questa azione non può essere annullata",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "rgb(170, 33, 33)",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sì, elimina",
            cancelButtonText: "Annulla"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/src/server/dashboard/delete_file.php?id=${encodeURIComponent(id)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Errore durante l'eliminazione del file");
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire("Eliminata!", "Il file è stato eliminato", "success")
                        .then(() => {
                            loadFile(); // Ricarica i file
                        });
                    } else {
                        Swal.fire("Errore", data.message, "error");
                    }
                })
                .catch(error => {
                    Swal.fire("Errore", "Si è verificato un errore durante l'eliminazione.", "error");
                    console.error("Errore:", error);
                });
            }
        });
    });

    return deleteButton;
}

// Funzione per creare una card recensione
function createRecensione(id,codice,nome,dat,testo,tipo) {
    const card = document.createElement("div");
    card.classList.add("rec");
    const formattedDate = new Date(dat).toLocaleDateString("it-IT", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric"
    });
    if(tipo === "esami"){
        card.addEventListener("click", () => {
        window.location.href = `/src/client/html/esame.php?codice=${encodeURIComponent(codice)}#recensioni`;
        });
    }
    else if(tipo === "professori"){
        card.addEventListener("click", () => {
        window.location.href = `/src/client/html/home_prof.php?id=${encodeURIComponent(codice)}#recensioni`;
        });
    }
    
    card.innerHTML = `
        <h2><b>${nome}</b>&emsp;&emsp;${formattedDate}</h2>
        <p>${testo}</p>
    `;
    const deleteButton = createDeleteButtonRecensione(id,tipo);
    card.appendChild(deleteButton);

    return card;
}

// Funzione per creare una card file
function createFile(id,codice,nome_esame,nome_file,dat) {
    const card = document.createElement("div");
    card.classList.add("rec");
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
    `;
    const deleteButton = createDeleteButtonFile(id);
    card.appendChild(deleteButton);

    return card;
}

// Funzione per caricare i preferiti dal database
function loadPreferiti() {
    const corsiContainer = document.querySelector(".corsi");
    const esamiContainer = document.querySelector(".esami");

    fetch(`/src/server/dashboard/get_corsi.php`)
    .then(response => { 
        if (!response.ok) {
            throw new Error("Errore durante il caricamento dei corsi.");
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            corsiContainer.innerHTML = "<h3><b>Corsi di laurea</b></h3>"; // Pulisci il contenitore prima di aggiungere i corsi
            if (data.corsi.length === 0) {
                const noCorsiMessage = document.createElement("p");
                noCorsiMessage.textContent = "Non hai ancora aggiunto un corso ai preferiti";
                corsiContainer.appendChild(noCorsiMessage);
            }

            // Aggiungi le card dei corsi
            data.corsi.forEach(corso => {
                const card = createCorso(corso.codice,corso.nome);
                corsiContainer.appendChild(card);
            });

        } else {
            console.error(data.message);
        }
    })
    .catch(error => {
        console.error("Errore:", error);
    });

    fetch(`/src/server/dashboard/get_esami.php`)
    .then(response => { 
        if (!response.ok) {
            throw new Error("Errore durante il caricamento degli esami.");                
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            esamiContainer.innerHTML = "<h3><b>Esami</b></h3>"; // Pulisci il contenitore prima di aggiungere gli esami
            
            if (data.esami.length === 0) {
                const noEsamiMessage = document.createElement("p");
                noEsamiMessage.textContent = "Non hai ancora aggiunto un esame ai preferiti";
                esamiContainer.appendChild(noEsamiMessage);
            }

            // Aggiungi le card dei corsi
            data.esami.forEach(esame => {
                const card = createEsame(esame.codice,esame.nome);
                esamiContainer.appendChild(card);
            });
        } else {
            console.error(data.message);
        }
    })
    .catch(error => {
        console.error("Errore:", error);
    });
}

// Funzione per caricare le recensioni dal database
function loadRecensioni() {
    const recesamiContainer = document.querySelector(".rec-esami");
    const recprofessoriContainer = document.querySelector(".rec-professori");

    fetch(`/src/server/dashboard/get_recensioni.php?tipo=esami`)
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
            noRecensioniMessage.textContent = "Non hai ancora lasciato nessuna recensione sugli esami";
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

    fetch(`/src/server/dashboard/get_recensioni.php?tipo=professori`)
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
                noRecensioniMessage.textContent = "Non hai ancora lasciato nessuna recensione";
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

// Funzione per caricare i file dal database
function loadFile() {
    const filesContainer = document.querySelector(".file");

    fetch(`/src/server/dashboard/get_file.php`)
    .then(response => { 
        if (!response.ok) {
            throw new Error("Errore durante il caricamento dei file");
        }
        return response.json();
        })
    .then(data => {
        if (data.success) {
            filesContainer.innerHTML = "<h2>Qui puoi trovare i file che hai caricato e condiviso</h2>"; // Pulisci il contenitore prima di aggiungere i file
            if (data.files.length === 0) {
            const noFileMessage = document.createElement("p");
            noFileMessage.textContent = "Non hai ancora condiviso nessun file";
            filesContainer.appendChild(noFileMessage);
            }

            // Aggiungi le card delle recensioni
            data.files.forEach(file => {
            const card = createFile(file.id,file.codice,file.nome,file.nomefile,file.dat);
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

function setupButtonGroup(buttons, defaultString, sections) {
  // Mappa tra i testi dei bottoni e i nomi delle sezioni
  const buttonToSectionMap = {
      "preferiti": "preferiti",
      "le tue recensioni": "recensioni",
      "i tuoi file": "file"
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

function logout(){
    Swal.fire({
        title: "Sei sicuro di voler uscire?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "rgb(170, 33, 33)",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sì, esci",
        cancelButtonText: "Annulla"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/src/server/auth/logout.php";
        }
    });
}

//Inizializza la pagina
document.addEventListener("DOMContentLoaded", function () {

    const navButtons = document.querySelectorAll('.nav-bar .bottone');
    const navSections = {
        "preferiti": document.querySelector(".preferiti"),
        "recensioni": document.querySelector(".recensioni"),
        "file": document.querySelector(".file")
    };
    // Imposta "professori" come default
    setupButtonGroup(navButtons, "preferiti", navSections);

    loadPreferiti();
    loadRecensioni();
    loadFile();
});


// Modifica della funzione per aggiornare l'immagine profilo con popup più grande
function modificaFoto(){
    Swal.fire({
        title: 'Modifica Immagine Profilo',
        width: '65%', // Popup meno largo
        html: `<input type="file" id="newProfilePic" accept="image/*" style="margin-top: 10px; width: 100%; font-size: 25px; padding: 4px;">
               <style>
                 .swal2-actions button {
                   font-size: 18px;
                   padding: 10px 20px;
                 }
               </style>`,
        showCancelButton: true,
        confirmButtonText: 'Aggiorna',
        confirmButtonColor: "rgb(170, 33, 33)", // Colore rosso come gli altri popup
        preConfirm: () => {
            const fileInput = document.getElementById('newProfilePic');
            if(fileInput.files.length === 0){
                Swal.showValidationMessage('Seleziona un file');
            }
            return fileInput.files[0];
        }
    }).then((result) => {
        if(result.isConfirmed){
            const formData = new FormData();
            formData.append('profilePic', result.value);
            fetch('/src/server/dashboard/update_profile_image.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if(!response.ok){
                    throw new Error("Errore durante l'aggiornamento");
                }
                return response.json();
            })
            .then(data => {
                if(data.success){
                    Swal.fire('Aggiornato!', data.message, 'success')
                    .then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore', data.message, 'error');
                }
            })
            .catch(err => {
                Swal.fire('Errore', 'Impossibile aggiornare l\'immagine', 'error');
                console.error(err);
            });
        }
    });
}