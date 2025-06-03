// Funzione per creare una card
function createCard(id,nome) {
    const card=document.createElement('div');
    card.classList.add("card");
    card.innerHTML=`
        <a href="/src/client/html/home_prof.php?id=${id}">
        <h3>${nome}</h3>
    </a>`;
    return card;
}

// Funzione per filtrare i professori
function filterProfessors(query) {
    const cards = document.querySelectorAll(".card");
    cards.forEach(card => {
        const professorName = card.querySelector("h3").textContent.toLowerCase();
        if (professorName.includes(query)) {
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
        filterProfessors(query);
    });

    const searchInput = document.getElementById("search-input");
    searchInput.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            const query = searchInput.value.toLowerCase();
            filterProfessors(query);
        }
    });
}

// Funzione per caricare i professori e gestire la ricerca
function loadProfessori(){
    const cardsContainer =document.querySelector(".content");

    fetch("/src/server/php/professori/get_professori.php")
    .then(response =>{
        if (!response.ok) {
            throw new Error("Errore durante il caricamento dei professori.");
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            data.professori.forEach(prof =>{
                const card=createCard(prof.id,prof.nome);
                cardsContainer.appendChild(card);
            });
            addSearchFunctionality();
        }
        else{
            console.log(data.message);
            cardsContainer.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";
        }    
    })
    .catch(error => {
        console.error("Errore:", error);
        cardsContainer.innerHTML = "<p class='errore'>Errore durante il caricamento dei dati.</p>";
    });
}

// Inizializza la pagina
document.addEventListener("DOMContentLoaded", function () {
    loadProfessori();
});