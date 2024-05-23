<?php
header('Content-Type: application/json');

include('config.php');

// Recupera l'ID della bicicletta dalla richiesta POST
$bikeId = $_POST['id'];

// Prepara una dichiarazione SQL per eliminare la bicicletta dalla tabella `biciclette`
$stmt = $conn->prepare("DELETE FROM `bicicletta` WHERE `bicicletta`.`id` = ?");
if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Errore nella preparazione della query: ' . $conn->error));
    exit();
}

// Associa l'ID del parametro alla dichiarazione SQL
$stmt->bind_param('i', $bikeId);

// Esegue la dichiarazione
if ($stmt->execute()) {
    echo json_encode(array('success' => true, 'message' => 'Bicicletta rimossa con successo.'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Errore nell\'esecuzione della query: ' . $stmt->error));
}

// Chiude la dichiarazione
$stmt->close();

// Chiude la connessione
$conn->close();
?>
