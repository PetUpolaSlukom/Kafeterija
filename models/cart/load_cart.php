<?php

    require_once('../../config/connection.php');
    require_once('../functions.php');

    $response = getCartProducts($_SESSION["user"]->id_korisnik);
    
    http_response_code(200);
    echo json_encode($response);
?>