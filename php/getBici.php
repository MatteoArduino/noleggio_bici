<?php
header('Content-Type: application/json');


include('config.php');

$query = "SELECT * FROM `bicicletta`";

$result = $conn->query($query);

if ($result === false) {
    die("Errore nella query: " . $conn->error);
}

$bici = array();

while ($row = $result->fetch_assoc()) {
    

    $bici[] = $row;
    
}

echo json_encode($bici);
