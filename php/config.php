<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "noleggio_bici_db";

// connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

$conn->set_charset("utf8mb4");

//controllo se si verifica un errore nella connessione al database
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
