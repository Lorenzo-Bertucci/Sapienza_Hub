document.addEventListener("DOMContentLoaded", function () {
    const cardsContainer = document.querySelector(".content");
    const searchInput = document.getElementById("search-input");
    const searchButton = document.querySelector(".search-bar button");

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

    // Funzione per caricare i corsi dal database
    function loadCorsi(searchQuery) {
        fetch(`/src/server/get_corsi.php`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Errore durante il caricamento dei corsi.");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {

                    // Aggiungi le card dei corsi
                    data.corsi.forEach(corso => {
                        const card = createCard(corso.codice,corso.nome, corso.tipo);
                        cardsContainer.appendChild(card);
                    });

                    // Ricollega gli eventi di ricerca
                    addSearchFunctionality();
                    // Imposta il valore della barra di ricerca
                    searchInput.value = searchQuery;

                    // Filtra i corsi in base al termine di ricerca
                    filterCourses(searchQuery);
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => {
                console.error("Errore:", error);
            });
    }

    // Funzione per filtrare i corsi
    function filterCourses(query) {
        const cards = document.querySelectorAll(".card");
        cards.forEach(card => {
            const courseName = card.querySelector("h3").textContent.toLowerCase();
            if (courseName.includes(query)) {
                card.style.display = "flex"; // Mostra la card
            } else {
                card.style.display = "none"; // Nascondi la card
            }
        });
        
    }

    // Aggiungi funzionalità di ricerca
    function addSearchFunctionality() {
        searchButton.addEventListener("click", function () {
            const query = searchInput.value.toLowerCase();

            filterCourses(query);
        });

        searchInput.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                const query = searchInput.value.toLowerCase();
      
                filterCourses(query);
            }
        });
    }

    // Recupera il termine di ricerca dall'URL
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get("search") || "";
    console.log("Search query:", searchQuery);

    // Imposta il valore della barra di ricerca e carica i corsi
    // La funzione filterCourses viene chiamata dopo il caricamento dei corsi
    loadCorsi(searchQuery);
});