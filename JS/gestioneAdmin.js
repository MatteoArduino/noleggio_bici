function loadStazioni() {
    $.ajax({
        type: "GET",
        url: "../php/getStazioni.php",
        success: function (response) {
            let stationSelect = $('#stationSelect');
            stationSelect.empty(); // Svuota la select prima di aggiungere nuove opzioni

            // Aggiungi la riga vuota con le caratteristiche desiderate
            const emptyOption = $('<option>', {
                value: '',
                text: 'Seleziona una stazione',
                disabled: true,
                selected: true
            });
            stationSelect.append(emptyOption);

            // Aggiungi le stazioni dal database
            response.forEach(station => {
                const option = $('<option>', {
                    value: station.id,
                    text: station.nome
                });
                stationSelect.append(option);
            });
        },
        error: function (xhr, status, error) {
            console.error("Errore AJAX: ", status, error);
        },
    });
}

function loadBici() {
    $.ajax({
        type: "GET",
        url: "../php/getBici.php",
        success: function (response) {
            let bikeSelect = $('#bikeSelect');
            bikeSelect.empty(); // Svuota la select prima di aggiungere nuove opzioni

            // Aggiungi la riga vuota con le caratteristiche desiderate
            const emptyOption = $('<option>', {
                value: '',
                text: 'Seleziona una bici',
                disabled: true,
                selected: true
            });
            bikeSelect.append(emptyOption);

            // Aggiungi le biciclette dal database
            response.forEach(bike => {
                const option = $('<option>', {
                    value: bike.id,
                    text: bike.id
                });
                bikeSelect.append(option);
            });
        },
        error: function (xhr, status, error) {
            console.error("Errore AJAX: ", status, error);
        },
    });
}





// funzione per aggiungere una stazione
function addStation() {

    let stationName = $('#stationName').val();
    let slotTotal = $('#slotTotal').val();
    let longitude = $('#longitude').val();
    let latitude = $('#latitude').val();

    if (stationName && slotTotal && longitude && latitude) {
        $.ajax({
            type: "POST",
            url: "../php/addStation.php",
            data: {
                stationName: stationName,
                slotTotal: slotTotal,
                longitude: longitude,
                latitude: latitude
            },
            success: function (response) {
                alert(response["message"]);
                loadStazioni(); // ricarica la lista delle stazioni
                $('#stationName').val('');
                $('#slotTotal').val('');
                $('#longitude').val('');
                $('#latitude').val('');
            },
            error: function (xhr, status, error) {
                console.error("Errore AJAX: ", status, error);
                alert('Errore durante l\'aggiunta della stazione. Per favore riprova.');
            }
        });
    } else {
        alert('Per favore, compila tutti i campi.');
    }
}

function addBici() {
    // Recupera i dati dai campi di input
    let bikeState = $('#bikeState').val();
    let kmTraveled = $('#kmTraveled').val();
    let gps = $('#gps').val();
    let rfid = $('#rfid').val();

    // Verifica se tutti i campi sono stati compilati
    if (bikeState && kmTraveled && gps && rfid) {
        $.ajax({
            type: "POST",
            url: "../php/addBici.php",
            data: {
                bikeState: bikeState,
                kmTraveled: kmTraveled,
                gps: gps,
                rfid: rfid
            },
            success: function (response) {
                alert(response.message);
                loadBici(); // ricarica la lista delle bici
                $('#bikeState').val('');
                $('#kmTraveled').val('');
                $('#gps').val('');
                $('#rfid').val('');
            },
            error: function (xhr, status, error) {
                console.error("Errore AJAX: ", status, error);
                alert('Errore durante l\'aggiunta della bicicletta. Per favore riprova.');
            }
        });
    } else {
        alert('Per favore, compila tutti i campi.');
    }
}


function removeStation() {

    let stationId = $('#stationSelect').val();

    if (stationId) {
        $.ajax({
            type: "POST",
            url: "../php/removeStation.php",
            data: { id: stationId },
            success: function (response) {
                if (response.success) {
                    alert("Stazione rimossa con successo");
                    loadStazioni(); // Ricarica la lista delle stazioni
                } else {
                    alert('Errore durante la rimozione della stazione: ' + response.message);
                }

            },
            error: function (xhr, status, error) {
                console.error("Errore AJAX: ", status, error);
            },
        });
    } else {
        alert('Seleziona una stazione da rimuovere');
    }
}



function removeBici() {
    let bikeId = $('#bikeSelect').val();
    if (bikeId) {
        $.ajax({
            type: "POST",
            url: "../php/removeBici.php", 
            data: { id: bikeId },
            success: function (response) {
                if (response.success) {
                    alert("Bicicletta rimossa con successo");
                    loadBici(); // Ricarica la lista delle biciclette
                } else {
                    alert('Errore durante la rimozione della bicicletta: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Errore AJAX: ", status, error);
            },
        });
    } else {
        alert('Seleziona una bicicletta da rimuovere');
    }
}
