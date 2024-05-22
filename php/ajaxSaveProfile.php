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

$sql = "UPDATE cliente SET nome = ?, cognome = ?, email = ?, password = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $nome, $cognome, $email, $password, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "message" => "Profilo aggiornato!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Errore durante l'aggiornamento del profilo"]);
}

$stmt->close();
$conn->close();

