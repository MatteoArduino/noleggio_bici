<?php
if (!isset($_SESSION)) {
    session_start();
}



// elimino tutte le variabili di sessione
session_unset();

// distruggo la sessione
session_destroy();

// reindirizzamento alla pagina di login o a un'altra pagina
header("Location: ../html/login.html");
exit();
