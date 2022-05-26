<?php

    $response = 0;
    require_once('../../config/connection.php');
    require_once('../functions.php');
    if (isset($_SESSION['user'])) {
        $response = getCartQuantity($_SESSION["user"]->id_korisnik);
    }
    
    
    http_response_code(200);
    echo json_encode($response);
?>