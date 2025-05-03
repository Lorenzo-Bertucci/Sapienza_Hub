document.addEventListener("DOMContentLoaded", function () {
    const cardsContainer = document.querySelector(".content");
    const searchInput = document.getElementById("search-input");
    const searchButton = document.querySelector(".search-bar button");

    // Funzione per creare una card
    function createCard(nome, tipo) {
        const card = document.createElement("div");
        card.classList.add("card");
        card.innerHTML = `
            <a>
                <h3>${nome}</h3>
                <p>~${tipo}</p>
            </a>
        `;
        return card;
    }

    // Funzione per caricare i corsi dal database
    function loadCorsi() {
        fetch("/src/server/get_corsi.php")
            .then(response => {
                if (!response.ok) {
                    throw new Error("Errore durante il caricamento dei corsi.");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Svuota il contenitore delle card
                    cardsContainer.innerHTML = `
                        <div class="search-bar">
                            <img src="assets/lente.png" alt="search" class="search" >
                            <input type="text" id="search-input" placeholder="Cerca il tuo corso di laurea">
                            <button>Cerca</button>
                        </div>
                        <h1>Lista dei Corsi di Laurea</h1>
                    `;

                    // Aggiungi le card dei corsi
                    data.corsi.forEach(corso => {
                        const card = createCard(corso.nome, corso.tipo);
                        cardsContainer.appendChild(card);
                    });

                    // Aggiungi eventi di ricerca
                    addSearchFunctionality();
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

    // Carica i corsi al caricamento della pagina
    loadCorsi();
});