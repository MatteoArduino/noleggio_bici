<?php
// settaggio della risposta in json 
header('Content-Type: application/json');

// inclusione del file config.php per la connessione al db
include ("config.php");

try {
    // recupero dei dati passati dall'utente
    $nome = $_POST["registraNome"];
    $cognome = $_POST["registraCognome"];
    $email = $_POST["registraEmail"];
    $password = $_POST["registraPassword"];
    $citta = $_POST["registraCitta"];
    $via = $_POST["registraVia"];
    $cap = $_POST["registraCap"];
    $civico = $_POST["registraCivico"];

    // generazione del codice della tessera tramite la combinazione
    // del md5 dell'email + l'md5 della password
    // prendendo solo i primi 8 caratteri
    $num_tessera = substr(md5($email) . md5($password), 0, 8);  //la password era già stata criptata in md5 nel js


    // inizio transazione
    $conn->begin_transaction();

    // query SQL per inserire l'indirizzo
    $sqlIndirizzo = "INSERT INTO `indirizzo` (`citta`, `cap`, `via`, `num_civico`) VALUES (?,?,?,?)";
    $stmtIndirizzo = $conn->prepare($sqlIndirizzo);
    if (!$stmtIndirizzo) {
        throw new Exception("Errore nella preparazione della query indirizzo: " . $conn->error);
    }
    $stmtIndirizzo->bind_param("ssss", $citta, $cap, $via, $civico);
    if (!$stmtIndirizzo->execute()) {
        throw new Exception("Errore durante l'esecuzione della query indirizzo: " . $stmtIndirizzo->error);
    }
    //recupero id dell'indirizzo appena inserito nel db
    $idIndirizzo = $conn->insert_id;

    // query SQL per inserire il cliente
    $sqlCliente = "INSERT INTO `cliente` (`nome`, `cognome`, `email`, `password`,`numero_tessera`, `id_indirizzo`) VALUES (?,?,?,?,?,?)";
    $stmtCliente = $conn->prepare($sqlCliente);
    if (!$stmtCliente) {
        throw new Exception("Errore nella preparazione della query cliente: " . $conn->error);
    }
    $stmtCliente->bind_param("sssssi", $nome, $cognome, $email, $password, $num_tessera, $idIndirizzo);
    if (!$stmtCliente->execute()) {
        throw new Exception("Errore durante l'esecuzione della query cliente: " . $stmtCliente->error);
    }

    // commit transazione
    $conn->commit();

    // restituzione del successo
    $arr = array("status" => "ok", "message" => "Registrazione avvenuta con successo");
    echo json_encode($arr);
} catch (Exception $e) {
    // rollback transazione in caso di errore
    $conn->rollback();
    // restituzione errore
    $arr = array("status" => "ko", "message" => $e->getMessage());
    echo json_encode($arr);
} finally {
    // chiusura statement
    if (isset($stmtIndirizzo)) {
        $stmtIndirizzo->close();
    }
    if (isset($stmtCliente)) {
        $stmtCliente->close();
    }
    // chiusura connessione
    $conn->close();
}

