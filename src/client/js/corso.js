// Funzione per ottenere un parametro dall'URL
function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// Funzione per creare una card per un esame
function createCard(codice,nome) {
    const card = document.createElement("div");
    card.classList.add("card");
    card.innerHTML = `
        <a href="/src/client/html/esame.php?codice=${encodeURIComponent(codice)}">
            <h3>${nome}</h3>
        </a>
    `;
    return card;
}

// Funzione per creare i div degli esami divisi per semestri
function createEsami(durata,esami) {
    const esamiContainer = document.querySelector(".esami");

    for (let anno = 1; anno <= durata; anno++) {
        // Creazione del div per il primo semestre
        const annoDiv1 = document.createElement("div");
        annoDiv1.classList.add(`anno-1-semestre-1`);
        annoDiv1.innerHTML = `<h1>${anno} Anno</h1> <h2>I Semestre</h2>`;
        esami.forEach(esame => {
            if (esame.anno == anno && esame.semestre == 1) {
                const card = createCard(esame.codice,esame.nome);
                annoDiv1.appendChild(card);
            }
        });
        esamiContainer.appendChild(annoDiv1);

        // Creazione del div per il secondo semestre
        const annoDiv2 = document.createElement("div");
        annoDiv2.classList.add(`anno-2-semestre-2`);
        annoDiv2.innerHTML = `<h2>II Semestre</h2>`;
        esami.forEach(esame => {
            if (esame.anno == anno && esame.semestre == 2) {
                const card = createCard(esame.codice,esame.nome);
                annoDiv2.appendChild(card);
            }
        });
        esamiContainer.appendChild(annoDiv2);
    }
}

// Funzione per caricare gli esami del corso
function load_esami(codice,durata) {
    const esamiContainer = document.querySelector(".esami");

    fetch(`/src/server/corso/get_esami.php?corso=${encodeURIComponent(codice)}`)
    .then(response => {
        if (!response.ok) {
            throw new Error("Errore durante il caricamento degli esami.");
        }
            return response.json();
    })
    .then(data => {
        if (data.success) {
            const esami = data.esami;
            if (esami.length === 0) {
                esamiContainer.innerHTML = "<p>Nessun esame trovato per questo corso.</p>";
                return;
            }
            createEsami(durata, esami);
        } else {
            console.error("Errore:",data.message);
            esamiContainer.innerHTML = "<p class='errore'>Errore durante il caricamento degli esami.</p>";
        }
    })
    .catch(error => {
        console.error("Errore:", error);
        esamiContainer.innerHTML = "<p class='errore'>Errore durante il caricamento degli esami.</p>";
    });
}

// Funzione per caricare i dati del corso di laurea
function loadCorso(codice) {
    const info = document.querySelector(".info");
    const desc = document.querySelector(".desc");

    fetch(`/src/server/corso/get_corso.php?corso=${encodeURIComponent(codice)}`)
    .then(response => {
        if (!response.ok) {
            throw new Error("Errore durante il caricamento dei dati.");
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const corso = data.corso;
            if (!corso.descrizione) {
                window.location.href = "/src/client/html/404.php";
                return;
            }
            document.title = `${corso.nome} - SapienzHub`;
            info.innerHTML = `
                <p><strong>Codice corso:</strong> ${corso.codice}</p>
                <p><strong>Durata:</strong> ${corso.durata} anni</p>
                <p><strong>Laurea:</strong> ${corso.laurea}</p>
                <p><strong>Lingua:</strong> ${corso.lingua}</p>
                <a href=${corso.link} style="color: black;" target="_blank">
                    <strong>Link del corso</strong>
                </a>
                <br><br>
            `;
            document.getElementById("corso-nome").textContent = corso.nome;
            document.getElementById("corso-descrizione").textContent = corso.descrizione;
            // Dopo aver ottenuto i dati del corso, imposta lo sfondo del main
            document.querySelector(".foto").style.backgroundImage = `url('assets/${corso.codice}.jpg')`;
            document.querySelector(".foto").style.backgroundRepeat = "no-repeat";
            document.querySelector(".foto").style.backgroundPosition = "center";
            document.querySelector(".foto").style.backgroundSize = "cover";
            load_esami(corso.codice,corso.durata);
        } else {
            console.error("Errore:", data.message);
            if(data.message == "Corso di laurea non trovato."){
                window.location.href = "/src/client/html/404.php";
                return;
            }
            desc.innerHTML = `<p class='errore'>Errore durante il caricamento dei dati.</p>`;
        }
    })
    .catch(error => {
        console.error("Errore:", error);
        desc.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";
    });
}

// Funzione per aggiornare lo stato del pulsante dei preferiti
function updateFavoriteState(isFavorite) {
    const favoriteBtn = document.querySelector(".favorite-btn");
    if (isFavorite) {
        favoriteBtn.classList.add("active");
        favoriteBtn.title = "Rimuovi dai preferiti";
    } else {
        favoriteBtn.classList.remove("active");
        favoriteBtn.title = "Aggiungi ai preferiti";
    }
}

// Funzione per gestire il click sul pulsante dei preferiti
function favorite() {
    const favoriteBtn = document.querySelector(".favorite-btn");
    const action = favoriteBtn.classList.contains("active") ? "remove" : "add";
    const corsoCodice = getUrlParameter("corso"); // Recupera il codice del corso dall'URL
    const corsoNome=document.getElementById("corso-nome").textContent

    fetch(`/src/server/corso/toggle_favorite.php`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({codice: corsoCodice,corso:corsoNome,action:action}),
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

// Funzione per controllare se il corso è nei preferiti
function checkFavorite(corso){
    fetch(`/src/server/corso/check_favorite.php?corso=${encodeURIComponent(corso)}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateFavoriteState(data.isFavorite);
        }
}); 
}

// Inizializza la pagina
document.addEventListener("DOMContentLoaded", function () {
    // Recupera il codice del corso di laurea dall'URL
    const corso = getUrlParameter("corso");

    if (!corso) {
        window.location.href = "/src/client/html/404.php";
        return;
    }

    checkFavorite(corso);

    loadCorso(corso);
});

