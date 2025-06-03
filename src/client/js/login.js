// Funzione per gestire il login
function inviaLogin(event){
    event.preventDefault();

    const form=document.getElementById("loginForm");
    const formData=new FormData(form);
 
    fetch('/src/server/php/auth/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Headers:', response.headers.get('Content-Type')); 
        if (!response.ok) {
            throw new Error('Errore durante il login');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log(data.user_id);
            
            localStorage.setItem("user_id", data.user_id);

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
            if(data.gestione){
                Swal.fire({
                title: 'Login Utente Speciale effettuato con successo!',
                text: 'Verrai reindirizzato a breve nella pagina di gestione',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
                });
                setTimeout(() => {
                    window.location.href = '../client/html/settings.php';
                }, 2000); 
            }
            else{
                Swal.fire('Errore!', data.message, 'error');
            }
        }
    })
    .catch(error => {
        Swal.fire('Errore!', error.message, 'error');
        console.error('Errore:', error);
    });
}

