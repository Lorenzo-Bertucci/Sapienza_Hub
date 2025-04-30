function inviaReg(event){
    event.preventDefault(); // Previene il comportamento predefinito del form

    // Recupera i dati dal form
    const form=document.getElementById("registerForm");
    const formData=new FormData(form);
    // Controlla il formato dell'email
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

    // Invia i dati al server tramite fetch
    fetch('/src/server/register.php', {
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
          Swal.fire('Registrazione effettuata con successo!', 'A breve verrai reindirizzato alla pagina di login', 'success');
            setTimeout(() => {
                window.location.href = '../client/html/login.php';
            }, 3000);
        } else {
          Swal.fire('Errore!', data.message, 'error');
        }
      })
}

