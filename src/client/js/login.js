function inviaLogin(event){
    event.preventDefault(); // Previene il comportamento predefinito del form

    // Recupera i dati dal form
    const form=document.getElementById("loginForm");
    const formData=new FormData(form);
 

    // Invia i dati al server tramite fetch
    fetch('/src/server/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Headers:', response.headers.get('Content-Type')); // Controlla il tipo di contenuto
        if (!response.ok) {
            throw new Error('Errore durante il login');
        }
        return response.json();
    })
    .then(data => {
        console.log('Risposta JSON:', data); // Log della risposta JSON
        if (data.success) {
            Swal.fire({
                title: 'Login effettuato con successo!',
                text: 'A breve verrai reindirizzato alla tua Dashboard',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });
            setTimeout(() => {
                window.location.href = '../client/html/dashboard.php';
            }, 2000);
        } else {
            Swal.fire('Errore!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Errore!', error.message, 'error');
        console.error('Errore:', error);
    });
}

