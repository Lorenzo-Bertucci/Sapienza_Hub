document.addEventListener("DOMContentLoaded", function () {
    const foto = document.querySelector(".foto");
    const info = document.querySelector(".info");
    const desc = document.querySelector(".desc");
    const esamiContainer = document.querySelector(".esami");

    // Funzione per ottenere il parametro "codice" dall'URL
    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Recupera il codice del corso di laurea dall'URL
    const corso = getUrlParameter("corso");

    if (!corso) {
        desc.innerHTML = "<p class='errore'>Codice del corso non specificato.</p>";
        return;
    }
    
    function createCard(codice,nome) {
        const card = document.createElement("div");
        card.classList.add("card");
        card.innerHTML = `
            <a href="/src/client/html/esame.php?corso=${encodeURIComponent(corso)}&codice=${encodeURIComponent(codice)}">
                <h3>${nome}</h3>
            </a>
        `;
        return card;
    }

    function createEsami(durata, esami) {
        for (let anno = 1; anno <= durata; anno++) {
            const annoDiv1 = document.createElement("div");
            annoDiv1.classList.add(`anno-1-semestre-1`);
            annoDiv1.innerHTML = `<h1>${anno} Anno</h1> <h2>I Semestre</h2>`;
            esami.forEach(esame => {
                if (esame.anno == anno && esame.semestre == 1) {
                    console.log(esame);
                    const card = createCard(esame.codice, esame.nome);
                    annoDiv1.appendChild(card);
                }
            });
            esamiContainer.appendChild(annoDiv1);
            const annoDiv2 = document.createElement("div");
            annoDiv2.classList.add(`anno-2-semestre-2`);
            annoDiv2.innerHTML = `<h2>II Semestre</h2>`;
            esami.forEach(esame => {
                if (esame.anno == anno && esame.semestre == 2) {
                    const card = createCard(esame.codice, esame.nome);
                    annoDiv2.appendChild(card);
                }
            });
            esamiContainer.appendChild(annoDiv2);
        }
    }



    // Funzione per caricare i dati del corso di laurea
    function loadCorso(codice) {
        fetch(`/src/server/get_laurea.php?corso=${encodeURIComponent(codice)}`)
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
                    foto.innerHTML = `<img src='assets/${corso.codice}.jpg' alt='${corso.nome}'>`;
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
                    desc.innerHTML = `
                        <h1>${corso.nome}</h1>
                        <p>${corso.descrizione}</p>
                    `;
                    load_esami(codice,corso.durata);
                } else {
                    desc.innerHTML = `<p class='errore'>${data.message}</p>`;
                }
            })
            .catch(error => {
                console.error("Errore:", error);
                desc.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";
            });
    }

    function load_esami(codice,durata) {
        fetch(`/src/server/get_esami.php?esami=esami_${encodeURIComponent(codice)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Errore durante il caricamento degli esami.");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const esami = data.data;
                    console.log(esami); 
                    if (esami.length === 0) {
                        esamiContainer.innerHTML = "<p>Nessun esame trovato per questo corso.</p>";
                        return;
                    }
                    createEsami(durata, esami);
                } else {
                    esamiContainer.innerHTML = `<p class='errore'>${data.message}</p>`;
                }
            })
            .catch(error => {
                console.error("Errore:", error);
                esamiContainer.innerHTML = "<p class='errore'>Errore durante il caricamento degli esami.</p>";
            });
    }

    // Carica i dati del corso di laurea
    loadCorso(corso);
});

