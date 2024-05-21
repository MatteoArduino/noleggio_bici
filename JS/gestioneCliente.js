//funzione per caricare le tratte percorse
function loadTratte() {
    $.ajax({
        type: "GET",
        url: "../php/ajaxGetTratte.php",
        success: function (response) {
            try {
                let tratteList = $("#tratte");
                tratteList.empty();
                if (response.status === "ok") {
                    response.data.forEach(function (tratta) {
                        // creo un div per ciascuna tratta con dettagli
                        let trattaDiv = `
                            <div class="tratta">
                                <span><b>ID:</b> ${tratta.id}</span>
                                <span><b>Data e Ora:</b> ${tratta.data_ora}</span>
                                <span><b>Distanza Percorsa:</b> ${tratta.distanza_percorsa}</span>
                                <span><b>Tipo:</b> ${tratta.tipo}</span>
                            </div>
                        `;
                        tratteList.append(trattaDiv);
                    });
                } else {
                    tratteList.append(`<div>${response.message}</div>`);
                }
            } catch (error) {
                alert("Errore nel parsing della risposta: " + error);
            }
        },
        error: function (xhr, status, error) {
            alert("Errore nella richiesta AJAX: " + status + " - " + error);
        }
    });
    
}

//funzione per caricare i riepiloghi
function loadRiepiloghi() {
    $.ajax({
        type: "GET",
        url: "../php/ajaxLoadRiepiloghi.php",
        success: function (response) {
            try {
                let riepiloghiList = $("#riepiloghi-list");
                riepiloghiList.empty();
                if (response["status"] == "ok") {
                    response["data"].forEach(function (riepilogo) {
                        riepiloghiList.append(`<div>${riepilogo}</div>`);
                    });
                } else {
                    riepiloghiList.append(`<div>${response["message"]}</div>`);
                }
            } catch (error) {
                alert(error);
            }
        },
    });
}

//funzione per caricare i dati del profilo
function loadProfile() {
    $.ajax({
        type: "GET",
        url: "../php/ajaxLoadProfile.php",
        success: function (response) {
            try {
                if (response["status"] == "ok") {
                    $("#nome").val(response["data"].nome);
                    $("#cognome").val(response["data"].cognome);
                    $("#email").val(response["data"].email);
                    $("#password").val(response["data"].password);
                } else {
                    alert(response["message"]);
                }
            } catch (error) {
                alert(error);
            }
        },
    });
}

//funzione per salvare i dati del profilo
function saveProfile() {
    let nome = $("#nome").val();
    let cognome = $("#cognome").val();
    let email = $("#email").val();
    let password = $("#password").val();

    if (nome == "" || cognome == "" || email == "" || password == "") {
        alert("ERRORE! Compilare tutti i campi");
    } else {
        let pswMD5 = CryptoJS.MD5(password).toString();

        $.ajax({
            type: "POST",
            url: "../php/ajaxSaveProfile.php",
            data: {
                nome: nome,
                cognome: cognome,
                email: email,
                password: pswMD5,
            },
            success: function (response) {
                try {
                    if (response["status"] == "ok") {
                        alert("Profilo aggiornato!");
                    } else {
                        alert(response["message"]);
                    }
                } catch (error) {
                    alert(error);
                }
            },
        });
    }
}