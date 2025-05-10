

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search-input");
    const searchButton = document.querySelector(".search-bar button");
    const cards = Array.from(document.querySelectorAll(".card"));

    function sortProfessors() {
        // Ordina le card in base al testo del tag <h3>
        const cardsContainer = document.querySelector(".content");
        const cards = Array.from(document.querySelectorAll(".card"));
        cards.sort((a, b) => {
            const nameA = a.querySelector("h3").textContent.toLowerCase();
            const nameB = b.querySelector("h3").textContent.toLowerCase();
            return nameA.localeCompare(nameB);
        });
    
        // Rimuovi tutte le card dal contenitore e riaggiungile nell'ordine corretto
        cards.forEach(card => cardsContainer.appendChild(card));
    }
    
    function loadProfessori(){
        const divLista=document.querySelector(".lista");
        fetch("/src/server/get_lista_prof.php")
        .then(response =>{
            if (!response.ok) {
                throw new Error("Errore durante il caricamento dei professori.");
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                    const prof = data.data;
                    divLista.innerHTML="<h1>Lista dei Professori:</h1>";
                    prof.forEach(profess =>{
                        const cardprof=document.createElement('div');
                        cardprof.classList.add("card");
                        cardprof.innerHTML=`
                                <a href="/src/client/html/home_prof.php?id=${profess.id}">
                                    <h3>${profess.nome}</h3>
                                </a>`;
                        divLista.appendChild(cardprof);
                    });
                    sortProfessors();

            }
            else{
                console.log(data.message);
                divLista.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";
            }    
            })
            .catch(error => {
                console.error("Errore:", error);
                divLista.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";
            });
    }

            // Funzione per eseguire la ricerca
    function searchProfessors() {
        const searchText = searchInput.value.toLowerCase();
        const cards = Array.from(document.querySelectorAll(".card"));

        cards.forEach(card => {
            const professorName = card.querySelector("h3").textContent.toLowerCase();

                    // Mostra o nascondi la card in base alla corrispondenza
            if (professorName.includes(searchText)) {
                card.style.display = "flex"; // Mostra la card
            } else {
                card.style.display = "none"; // Nascondi la card
            }
        });
    }

            // Avvia la ricerca quando si preme il pulsante "Cerca"
    searchButton.addEventListener("click", searchProfessors);

            // Avvia la ricerca quando si preme "Invio" nell'input
    searchInput.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            searchProfessors();
        }
    });
    loadProfessori();
    
});