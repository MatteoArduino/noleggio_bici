<?php
header('Content-Type: application/json');

include('config.php');

// Recupera i dati inviati dalla richiesta POST
$bikeState = $_POST['bikeState'];
$kmTraveled = $_POST['kmTraveled'];
$gps = $_POST['gps'];
$rfid = $_POST['rfid'];

// Prepara una dichiarazione SQL per inserire i dati nella tabella `biciclette`
$stmt = $conn->prepare("INSERT INTO `bicicletta` (stato, km_percorsi, gps, rfid) VALUES (?, ?, ?, ?)");
if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Errore nella preparazione della query: ' . $conn->error));
    exit();
}

// Associa i parametri alla dichiarazione SQL
$stmt->bind_param('siss', $bikeState, $kmTraveled, $gps, $rfid);

// Esegue la dichiarazione
if ($stmt->execute()) {
    echo json_encode(array('success' => true, 'message' => 'Bicicletta aggiunta con successo.'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Errore nell\'esecuzione della query: ' . $stmt->error));
}

// Chiude la dichiarazione
$stmt->close();

// Chiude la connessione
$conn->close();
?>
