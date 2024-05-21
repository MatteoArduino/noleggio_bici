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

// Query per ottenere le tratte dell'utente specifico
$sql = "
    SELECT o.id, o.data_ora, o.distanza_percorsa, o.tipo 
    FROM operazione AS o
    JOIN cliente AS c ON c.id = o.user_id
    WHERE o.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$tratte = [];
while ($row = $result->fetch_assoc()) {
    $tratte[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode(["status" => "ok", "data" => $tratte]);

