//metodo che permette di loggarsi al sito controllando le credenziali
function login() {
  //ottenimento username
  let email = $("#loginEmail").val();
  //ottenimento password
  let password = $("#loginPassword").val();

  //ottenimento ruolo (client o admin)
  let role = $("#role").val();

  //controllo se l'utente ha fornito un input valido
  if (email == "" || password == "") {
    //visualizzazione errore (input incompleto)
    alert("ERRORE! Compilare tutti i campi");
  }
  //input corretto
  else {
    //cripting password
    let pswMD5 = CryptoJS.MD5(password).toString();

    $.ajax({
      type: "POST",
      url: "../php/ajaxLogin.php",
      data: { loginEmail: email, loginPassword: pswMD5, role: role },
      success: function (response) {
        try {
          //controllo che il login sia andato a buon fine
          if (response["status"] == "ok") {
            //controllo se l'utente loggato è l'admin o un utente comune
            if (response["ruolo"] == "admin") {
              //reindirizzamento alla pagina dell'admin
              window.location.href = "../html/paginaAdmin.html";
            }
            //se l'utente loggato non è admin
            else {
              window.location.href = "../html/paginaCliente.html";
              
            }
          }
          else {

            alert("errore");
          }

          //gestone errori
        } catch (error) {
          //visualizzazione errore
          alert(error);
        }
      },
    });
  }
}

function registra() {
  //ottenimento nome
  let nome = $("#registraNome").val();
  //ottenimento cognome
  let cognome = $("#registraCognome").val();
  //ottenimento email
  let email = $("#registraEmail").val();
  //ottenimento password
  let password = $("#registraPassword").val();
  //ottenimento citta
  let citta = $("#registraCitta").val();
  //ottenimento via
  let via = $("#registraVia").val();
  //ottenimento cap
  let cap = $("#registraCap").val();
  //ottenimento numero civico
  let civico = $("#registraCivico").val();

  //controllo se l'utente ha fornito un input valido
  if (nome == "" || cognome == "" || email == "" || password == "" || citta == "" || via == "" || cap == "" || civico == "") {
    //visualizzazione errore (input incompleto)
    alert("ERRORE! Compilare tutti i campi");
  }
  //input corretto
  else {
    //cripting password
    let pswMD5 = CryptoJS.MD5(password).toString();

    $.ajax({
      type: "POST",
      url: "../php/ajaxRegistra.php",
      data: { registraNome: nome, registraCognome: cognome, registraEmail: email,registraPassword: pswMD5,registraCitta: citta,registraVia: via,registraCap:cap, registraCivico:civico},
      success: function (response) {
        try {
          //controllo che la registrazione sia andato a buon fine
          if (response["status"] == "ok") {
            alert(response["message"]);
          }
          else{

            alert(response["message"]);
          }

          //gestone errori
        } catch (error) {
          //visualizzazione errore
          alert(error);
        }
      },
    });
  }
}

