<?php
include("config.php");
header("Content-Type: application/json");

date_default_timezone_set('Europe/Rome');

function aggiorna($conn, $query, $param){
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $param);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["idStazione"]) && isset($_POST["idBici"]) && isset($_POST["idCliente"])) {

        try {
            // Inizia la transazione
            $conn->begin_transaction();

            // Verifica il numero di bici disponibili in quella stazione
            $query = "SELECT num_bici_disp FROM stazione WHERE ID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $_POST["idStazione"]);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row["num_bici_disp"] > 0) {
                    // Aggiorna il numero di bici disponibili nella stazione
                    $query = "UPDATE stazione SET num_bici_disp = num_bici_disp - 1 WHERE ID = ?";
                    aggiorna($conn, $query, $_POST["idStazione"]);

                    // Aggiorna il flag di disponibilità della bici
                    $query = "UPDATE bici SET disponibile = FALSE WHERE ID = ?";
                    aggiorna($conn, $query, $_POST["idBici"]);

                    // Inserisce il noleggio nella tabella operazione
                    $query = "INSERT INTO operazione (tipo, data_ora, idCliente, idBici, idStazione) VALUES (?, ?, ?, ?, ?)";
                    $dataOra = date('Y-m-d H:i:s');
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssiii", "noleggio", $dataOra, $_POST["idCliente"], $_POST["idBici"], $_POST["idStazione"]);
                    $stmt->execute();

                    // Commit della transazione
                    $conn->commit();
                    echo json_encode(array("status" => "success", "code" => 200, "message"=> "Operazione di noleggio completata con successo"));
                } else {
                    throw new Exception("Le bici nella stazione sono esaurite o la stazione non esiste", 404);
                }
            } else {
                throw new Exception("Le bici nella stazione sono esaurite o la stazione non esiste", 404);
            }
        } catch (Exception $e) {
            // Rollback della transazione
            $conn->rollback();
            $code = $e->getCode() ?: 500;
            http_response_code($code);
            echo json_encode(array("status" => "error", "code" => $code, "message" => $e->getMessage()));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("status" => "error", "code" => 400, "message" => "Manca una o più variabili"));
    }
} else {
    http_response_code(405);
    echo json_encode(array("status" => "error", "code" => 405, "message" => "Method Not Allowed"));
}

