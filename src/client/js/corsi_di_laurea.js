// Funzione per creare una card
function createCard(codice,nome, tipo) {
    const card = document.createElement("div");
    card.classList.add("card");
    card.innerHTML = `
        <a href="/src/client/html/laurea.php?corso=${encodeURIComponent(codice)}">
            <h3>${nome}</h3>
            <p>~${tipo}</p>
        </a>
    `;
    return card;
}

// Funzione per filtrare i corsi
function filterCourses(query) {
    const cards = document.querySelectorAll(".card");
    cards.forEach(card => {
        const courseName = card.querySelector("h3").textContent.toLowerCase();
        if (courseName.includes(query)) {
            card.style.display = "flex";
        } else {
            card.style.display = "none"; 
        }
    });    
}

// Aggiungi funzionalità di ricerca
function addSearchFunctionality() {
    const searchButton = document.querySelector(".search-bar button");

    searchButton.addEventListener("click", function () {
        const query = searchInput.value.toLowerCase();
        filterCourses(query);
    });

    const searchInput = document.getElementById("search-input");
    searchInput.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            const query = searchInput.value.toLowerCase();
            filterCourses(query);
        }
    });
}

// Funzione per caricare i corsi dal database
function loadCorsi(searchQuery) {
    const cardsContainer = document.querySelector(".content");
    const searchInput = document.getElementById("search-input");

    fetch(`/src/server/php/corsi_di_laurea/get_corsi.php`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Errore durante il caricamento dei corsi.");
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                data.corsi.forEach(corso => {
                    const card = createCard(corso.codice,corso.nome,corso.tipo);
                    cardsContainer.appendChild(card);
                });
                addSearchFunctionality();
                searchInput.value = searchQuery;
                filterCourses(searchQuery);
            } else {
                console.error("Errore:",data.message);
                cardsContainer.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";

            }
        })
        .catch(error => {
            console.error("Errore:", error);
            cardsContainer.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";
        });
}

// Inizializza la pagina
// Carica i corsi e aggiungi la funzionalità di ricerca
document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get("search") || "";

    loadCorsi(searchQuery);
});