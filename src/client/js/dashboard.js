document.addEventListener("DOMContentLoaded", function () {
    const corsiContainer = document.querySelector(".corsi");
    const esamiContainer = document.querySelector(".esami");
    const recesamiContainer = document.querySelector(".rec-esami");
    const recprofessoriContainer = document.querySelector(".rec-professori");
    const filesContainer = document.querySelector(".file");

    // Funzione per creare una card
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
            confirmButtonColor: "#d33",
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
                        location.reload(); // Ricarica la pagina
                    });
                } else {
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
            confirmButtonColor: "#d33",
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
                        location.reload(); // Ricarica la pagina
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

    

    // Funzione per caricare i corsi dal database
    function loadPreferiti() {
        fetch(`/src/server/dashboard/get_corsi.php`)
            .then(response => { 
                if (!response.ok) {
                    throw new Error("Errore durante il caricamento dei corsi.");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
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

    function loadRecensioni() {
        fetch(`/src/server/dashboard/get_recensioni.php?tipo=esami`)
            .then(response => { 
            if (!response.ok) {
                throw new Error("Errore durante il caricamento delle recensioni degli esami");
            }
            return response.json();
            })
            .then(data => {
            if (data.success) {
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

    function loadFile() {
        fetch(`/src/server/dashboard/get_file.php`)
            .then(response => { 
            if (!response.ok) {
                throw new Error("Errore durante il caricamento dei file");
            }
            return response.json();
            })
            .then(data => {
            if (data.success) {
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

    loadPreferiti();
    loadRecensioni();
    loadFile();

});