<?php
header('Content-Type: application/json');

include('config.php');

// Verifica se i dati sono stati inviati tramite POST
$id = $_POST['id'];

if ($id) {
    
    $stmt = $conn->prepare("DELETE FROM `stazione` WHERE `stazione`.`id` = ?");
    if ($stmt === false) {
        echo json_encode(array('success' => false, 'message' => 'Errore nella preparazione della query: ' . $conn->error));
        exit();
    }

    // Associa i parametri alla dichiarazione SQL
    $stmt->bind_param('i', $id);

    // Esegue la dichiarazione
    if ($stmt->execute()) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Errore nell\'esecuzione della query: ' . $stmt->error));
    }

    // Chiude la dichiarazione
    $stmt->close();
} else {
    echo json_encode(array('success' => false, 'message' => 'ID stazione non valido'));
}

// Chiude la connessione
$conn->close();

