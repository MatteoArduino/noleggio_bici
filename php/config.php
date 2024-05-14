<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "noleggio_bici_db";

// connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

$conn->set_charset("utf8mb4");
// verifica la connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
