// Funzione per gestire la registrazione
function inviaReg(event){
  event.preventDefault(); 

  const selectElem = document.getElementById("corso");
  console.log("Valore select:", selectElem.value);

  const form=document.getElementById("registerForm");
  const formData=new FormData(form);

  console.log("CORSO:" ,formData.get("inputCorso"));

  const email = formData.get('inputEmail');
  const cognome = formData.get('inputCognome');
  const emailCognome = email.split('.')[0];
  const emailRegex = /^[a-zA-Z]+\.\d{7}@studenti\.uniroma1\.it$/;
  if (!emailRegex.test(email)) {
    Swal.fire('Errore!', 'L\'email deve essere quella istituzionale sapienza', 'error');
    return;
  }

  if (cognome.toLowerCase() !== emailCognome.toLowerCase()) {
    Swal.fire('Errore!', 'Il cognome deve coincidere con il cognome presente nell\'email', 'error');
    return;
  }

  fetch('/src/server/php/auth/register.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Errore durante l\'invio della registrazione.');
    }
    return response.json(); 
  })
  .then(data => {
    if (data.success) {
      Swal.fire({
        title: 'Registrazione effettuata con successo!',
        text: 'A breve verrai reindirizzato alla pagina di login',
        icon: 'success',
        showConfirmButton: false,
        timer: 2000
      });
      setTimeout(() => {
        window.location.href = '../client/html/login.php';
      }, 2000);
    } else {
      Swal.fire('Errore!', data.message, 'error');
    }
  })
  .catch(error => {
    console.error('Errore:', error);
    Swal.fire('Errore!', 'Si è verificato un errore durante la registrazione.', 'error');
  });
}

// Funzione per recuperare i corsi dal server e popolare il select
function getCorsi(){
  fetch('/src/server/php/auth/get_corsi.php')
    .then(response => {
        if (!response.ok) {
            throw new Error("Errore nel caricamento dei corsi");
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const corsoSelect = document.getElementById("corso");
            data.corsi.forEach(corso => {
                const opt = document.createElement("option");
                opt.value = corso.codice;
                opt.textContent = corso.nome;
                corsoSelect.appendChild(opt);
            });
        } else {
            console.error("Impossibile recuperare i corsi:", data.message);
        }
    })
    .catch(error => {
        console.error("Errore:", error);
    });
}


document.addEventListener("DOMContentLoaded", function() {
    const input = document.getElementById("profilePic");
    const preview = document.getElementById("preview");
    const defaultImg = "assets/utente.png";

    preview.addEventListener("click", () => input.click());
    preview.addEventListener("keypress", (e) => {
        if (e.key === "Enter" || e.key === " ") input.click();
    });

    input.addEventListener("change", function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = defaultImg;
        }
    });

    preview.onerror = function() {
        preview.src = defaultImg;
    };

    getCorsi();
});