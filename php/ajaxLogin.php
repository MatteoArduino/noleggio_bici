<?php
    //settaggio della risposta in json 
    header('Content-Type: application/json');
    
    //inclusione del file config.php per la connessione al db
    include("config.php");

    try{

        //recupero dell'username e la password passati dall'utente
        $email=$_POST["loginEmail"];
        $password=$_POST["loginPassword"];
        $role = $_POST["role"];

        // $email = "gianluca.torre@gmail.com";
        // $password = md5("password");


        //query sql per ricercare l'utente inserito dall'utente nel database
        $sql= "SELECT * FROM `$role` WHERE email = ? AND password = ?";

        //preparazione della query per verificare eventuali errori
        $stmt = $conn->prepare($sql);

        //controllo se la preparazione della query presenta errori
        if (!$stmt) 
            //comunicazione di eventuali errori
            throw new Exception("Errore nella preparazione della query: " . $conn->error);
        
        //inserimento dei parametri all'interno della query preparata
        //ss --> 2 stringhe
        $stmt->bind_param("ss", $email, $password);
        
        //esecuzione della query creata
        if (!$stmt->execute()) 
            //comunicazione dell'errore nell'esecuzione della query
            throw new Exception("Errore durante l'esecuzione della query: " . $stmt->error);

        //salvataggio risultati in apposita variabile
        $result = $stmt->get_result();

        //controllo se c'è una riga di risultato
        if ($result->num_rows == 1) {
            //estrazione del risultato della queryò
            $row = $result->fetch_assoc();
           

            //avvio sessione
            session_start();
            //salvataggio dell'username e dell'id nella variabile di sessione
            $_SESSION["email"] = $email;
            $_SESSION["userID"] = $row["id"];

            //salvataggio della risposta in un nuovo array
            $arr = array("status" => "ok", "message" => "Login effettuato", "ruolo" => $role);
            //conversione dell'array in formato json e visualizzazione
            echo json_encode($arr);
            
        }
        //se il risultato non c'è (non possono esserci più righe uguali)
        else {
            //salvataggio della risposta in un nuovo array
            $arr = array("status" => "ko", "message" => "Credenziali non valide");
            //conversione dell'array in formato json e return a js
            echo json_encode($arr);
            
        }
    //gestione eccezioni
    } catch (Exception $e) {
        //login non andato a buon fine
        $arr = array("status" => "ko", "message" => $e->getMessage());
        //return a js
        echo json_encode($arr);
        
    }