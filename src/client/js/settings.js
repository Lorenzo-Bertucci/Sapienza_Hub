function gestisciAzione(azione){
    const divTool=document.querySelector(".tool");
    divTool.innerHTML=`<h1>${azione}</h1>`;
    const form = document.createElement('form');
    form.classList.add("form-element");
    const div= document.createElement('div');
    div.classList.add("lista");
    switch (azione) {
        case "Aggiungi/Aggiorna Corso di Studi":
            form.innerHTML = `<input type="number" name="codice" placeholder="inserire codice corso" required><br><br>
                                <input type="text" name="nome" placeholder="inserire nome corso" required><br><br>
                                <input type="text" name="tipo" placeholder="inserire tipo corso" required><br><br>
                                <input type="text" name="laurea" placeholder="inserire laurea di appartenenza del corso"required><br><br>
                                <input type="text" name="lingua" placeholder="inserire lingua corso"required><br><br>
                                <input type="text" name="link" placeholder="inserire link corso"required><br><br>
                                <input type="number" name="durata" placeholder="inserire durata corso"required><br><br>
                                <input type="text" name="desc" placeholder="inserire descrizione corso"required><br><br>
                                <button type="submit">Aggiungi</button>
                            </form>`;
            form.addEventListener('submit', add_corso);
            divTool.appendChild(form);
            break;
        case "Rimuovi Corso di Studi":
            div.innerHTML="<h3>Lista Corsi di studi</h3>";
            fetch('/src/server/php/gestione/lista_corsi.php')
                .then(response => response.json())
                .then(corsi => {
                    if (corsi.length === 0) {
                        div.innerHTML+= "Nessun corso trovato.";
                    } else {
                        div.innerHTML+="<h4>Codice - Corso</h4>";
                        div.innerHTML+= corsi.map(corso => `${corso.codice} - ${corso.nome}<br>`).join('');
                    }
                })
                .catch(() => {
                    div.innerHTML+= "Errore nel caricamento dei corsi.";
                });
            form.innerHTML = `<input type="number" name="codice" placeholder="inserire codice corso" required><br><br>
                                <button type="submit">Rimuovi</button>
                            </form>`;
            divTool.appendChild(div);
            form.addEventListener('submit', remove_corso);
            divTool.appendChild(form);
            break;
        case "Aggiungi/Aggiorna Esame":
            form.innerHTML = `<input type="text" name="codice" placeholder="inserire codice esame" required><br><br>
                                <input type="text" name="nome" placeholder="inserire nome esame" required><br><br>
                                <input type="number" name="anno" placeholder="inserire anno svolgimento dell'esame" required><br><br>
                                <input type="number" name="semestre" placeholder="inserire semestre di svolgimento dell'esame" required><br><br>
                                <input type="number" name="cfu" placeholder="inserire cfu esame"><br><br>
                                <input type="text" name="ssd" placeholder="inserire ssd esame"><br><br>
                                <input type="text" name="lingua" placeholder="inserire lingua esame"><br><br>
                                <input type="text" name="tipo" placeholder="inserire tipo esame"><br><br>
                                <input type="text" name="ambito" placeholder="inserire ambito esame"><br><br>
                                <input type="text" name="link" placeholder="inserire link esame"><br><br>
                                <input type="text" name="desc" placeholder="inserire descrizione esame"><br><br>
                                <input type="number" name="corso" placeholder="inserire corso di appartenenza dell'esame" required><br><br>
                                <button type="submit">Aggiungi</button>
                            </form>`;
            form.addEventListener('submit', add_esame);
            divTool.appendChild(form);
            
            break;
        case "Rimuovi Esame":

            div.innerHTML="<h3>Lista Esami</h3>";
            fetch('/src/server/php/gestione/lista_esami.php')
                .then(response => response.json())
                .then(esami => {
                    if (esami.length === 0) {
                        div.innerHTML+= "Nessun esame trovato.";
                    } else {
                        div.innerHTML+="<h4>Codice - Esame</h4>";
                        div.innerHTML+= esami.map(esame => `${esame.codice} - ${esame.nome}<br>`).join('');
                    }
                })
                .catch(() => {
                    div.innerHTML+= "Errore nel caricamento degli esami.";
                });
            form.innerHTML = `<input type="text" name="codice" placeholder="inserire codice esame" required><br><br>
                                <button type="submit">Rimuovi</button>
                            </form>`;
            divTool.appendChild(div);
            form.addEventListener('submit', remove_esame);
            divTool.appendChild(form);
            
            break;
        case "Aggiungi/Aggiorna Professore":

            form.innerHTML = `<input type="text" name="nome" placeholder="inserire nome professore" required><br><br>
                                <input type="text" name="email" placeholder="inserire email professore"><br><br>
                                <input type="text" name="dip" placeholder="inserire dipartimento professore"><br><br>
                                <input type="text" name="ssd" placeholder="inserire ssd professore"><br><br>
                                <input type="text" name="link" placeholder="inserire link  pagina professore"><br><br>
                                <button type="submit">Aggiungi</button>
                            </form>`;
            form.addEventListener('submit', add_professore);
            divTool.appendChild(form);
            
            break;
        case "Rimuovi Professore":

            div.innerHTML="<h3>Lista professori</h3>";
            fetch('/src/server/php/gestione/lista_professori.php')
                .then(response => response.json())
                .then(professori => {
                    if (professori.length === 0) {
                        div.innerHTML+= "Nessun professore trovato.";
                    } else {
                        div.innerHTML+="<h4>id - Nome</h4>";
                        div.innerHTML+= professori.map(professore => `${professore.id} - ${professore.nome}<br>`).join('');
                    }
                })
                .catch(() => {
                    div.innerHTML+= "Errore nel caricamento dei professori.";
                });
            form.innerHTML = `<input type="number" name="id" placeholder="inserire id professore" required><br><br>
                                <button type="submit">Rimuovi</button>
                            </form>`;
            divTool.appendChild(div);
            form.addEventListener('submit', remove_professore);
            divTool.appendChild(form);
            
            break;
        case "Aggiungi Utente Privilegiato":

            form.innerHTML = `<input type="text" name="utente" placeholder="inserire nome utente/mail" required><br><br>
                                <input type="password" name="password" placeholder="inserire password" required><br><br>
                                <button type="submit">Aggiungi</button>
                            </form>`;
            form.addEventListener('submit', add_utente);
            divTool.appendChild(form);
            
            break;
        case "Rimuovi Utente Privilegiato":

            div.innerHTML="<h3>Lista Utenti Privilegiati</h3>";
            fetch('/src/server/php/gestione/lista_utenti.php')
                .then(response => response.json())
                .then(utenti => {
                    if (utenti.length === 0) {
                        div.innerHTML+= "Nessun utente privilegiato trovato.";
                    } else {
                        div.innerHTML+="<h4>Nome utente</h4>";
                        div.innerHTML+= utenti.map(utente => `${utente.utente}<br>`).join('');
                    }
                })
                .catch(() => {
                    div.innerHTML+= "Errore nel caricamento degli utenti personalizzati.";
                });
            form.innerHTML = `<input type="text" name="utente" placeholder="inserire nome/mail utente" required><br><br>
                                <button type="submit">Rimuovi</button>
                            </form>`;
            divTool.appendChild(div);
            form.addEventListener('submit', remove_utente);
            divTool.appendChild(form);
            
            break;
    }

}

function add_corso(e){
    e.preventDefault();
    const formData=new FormData(document.querySelector(".form-element"));
    fetch('/src/server/php/gestione/add_corso.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            alert('Errore durante l\'inserimento del corso.');
        }
        return response.json(); 
    })
    .then(data => {
    if (data.success) {
        alert(data.message);
    } else {
        alert(data.message);
    }
    })
    .catch(error => {
        alert('Errore durante l\'inserimento del corso!');
    });
}

function remove_corso(e){
    e.preventDefault();
    const formData=new FormData(document.querySelector(".form-element"));
    fetch('/src/server/php/gestione/remove_corso.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            alert('Errore durante l\'eliminazione del corso.');
        }
        return response.json(); 
    })
    .then(data => {
    if (data.success) {
        alert(data.message);
    } else {
        alert(data.message);
    }
    })
    .catch(error => {
        alert('Errore durante l\'eliminazione del corso!');
    });
}

function add_esame(e){
    e.preventDefault();
    const formData=new FormData(document.querySelector(".form-element"));
    fetch('/src/server/php/gestione/add_esame.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            alert('Errore durante l\'inserimento dell\'esame.');
        }
        return response.json(); 
    })
    .then(data => {
    if (data.success) {
        alert(data.message);
    } else {
        alert(data.message);
    }
    })
    .catch(error => {
        alert('Errore durante l\'inserimento dell\'esame!');
    });
}

function remove_esame(e){
    e.preventDefault();
    const formData=new FormData(document.querySelector(".form-element"));
    fetch('/src/server/php/gestione/remove_esame.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            alert('Errore durante l\'eliminazione dell\'esame.');
        }
        return response.json(); 
    })
    .then(data => {
    if (data.success) {
        alert(data.message);
    } else {
        alert(data.message);
    }
    })
    .catch(error => {
        alert('Errore durante l\'eliminazione dell\'esame!');
    });
}

function add_professore(e){
    e.preventDefault();
    const formData=new FormData(document.querySelector(".form-element"));
    fetch('/src/server/php/gestione/add_professore.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            alert('Errore durante l\'inserimento del professore.');
        }
        return response.json(); 
    })
    .then(data => {
    if (data.success) {
        alert(data.message);
    } else {
        alert(data.message);
    }
    })
    .catch(error => {
        alert('Errore durante l\'inserimento del professore!');
    });
}

function remove_professore(e){
    e.preventDefault();
    const formData=new FormData(document.querySelector(".form-element"));
    fetch('/src/server/php/gestione/remove_professore.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            alert('Errore durante l\'eliminazione del professore.');
        }
        return response.json(); 
    })
    .then(data => {
    if (data.success) {
        alert(data.message);
    } else {
        alert(data.message);
    }
    })
    .catch(error => {
        alert('Errore durante l\'eliminazione del professore!');
    });
}

function add_utente(e){
    e.preventDefault();
    const formData=new FormData(document.querySelector(".form-element"));
    fetch('/src/server/php/gestione/add_utente.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            alert('Errore durante l\'inserimento dell\'utente.');
        }
        return response.json(); 
    })
    .then(data => {
    if (data.success) {
        alert(data.message);
    } else {
        alert(data.message);
    }
    })
    .catch(error => {
        alert('Errore durante l\'inserimento dell\'utente!');
    });
}

function remove_utente(e){
    e.preventDefault();
    const formData=new FormData(document.querySelector(".form-element"));
    fetch('/src/server/php/gestione/remove_utente.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            alert('Errore durante l\'eliminazione dell\'utente.');
        }
        return response.json(); 
    })
    .then(data => {
    if (data.success) {
        alert(data.message);
    } else {
        alert(data.message);
    }
    })
    .catch(error => {
        alert('Errore durante l\'eliminazione dell\'utente!');
    });
}