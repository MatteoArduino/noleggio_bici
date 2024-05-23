<?php
header('Content-Type: application/json');

include('config.php');

// Recupera i dati inviati dalla richiesta POST
$stationName = $_POST['stationName'];
$slotTotal = $_POST['slotTotal'];
$longitude = $_POST['longitude'];
$latitude = $_POST['latitude'];


$stmt = $conn->prepare("INSERT INTO `stazione` (nome, slot_totali, lon, lat) VALUES (?, ?, ?, ?)");
if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Errore nella preparazione della query: ' . $conn->error));
    exit();
}

// Associa i parametri alla dichiarazione SQL
$stmt->bind_param('siss', $stationName, $slotTotal, $longitude, $latitude);

// Esegue la dichiarazione
if ($stmt->execute()) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false, 'message' => 'Errore nell\'esecuzione della query: ' . $stmt->error));
}

// Chiude la dichiarazione
$stmt->close();

// Chiude la connessione
$conn->close();
?>
