<?php
header('Content-Type: application/json');

include("config.php");

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
    echo json_encode(["status" => "error", "message" => "Utente non loggato"]);
    exit();
}

$user_id = $_SESSION['userID'];
$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$email = $_POST['email'];
$password = $_POST['password'];
$carta_credito = $_POST['carta_credito']; // Aggiunto il recupero del numero della carta di credito

// Modifica la query per includere anche il numero della carta di credito
$sql = "UPDATE cliente SET nome = ?, cognome = ?, email = ?, password = ?, numero_carta = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $nome, $cognome, $email, $password, $carta_credito, $user_id); // Aggiunto il bind del numero della carta di credito

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "message" => "Profilo aggiornato!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Errore durante l'aggiornamento del profilo"]);
}

$stmt->close();
$conn->close();


