<?php
header('Content-Type: application/json');


include('config.php');

$query = "SELECT * FROM `stazione`";

$result = $conn->query($query);

if ($result === false) {
    die("Errore nella query: " . $conn->error);
}

$stazioni = array();

while ($row = $result->fetch_assoc()) {
    

    $stazioni[] = $row;
    
}

echo json_encode($stazioni);
