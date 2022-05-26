<?php
    header("Content-type: application/json");


    require_once "../../config/connection.php";
    require_once "../functions.php";

    $userObj = false;
    if(isset($_SESSION['user'])) {
        $userObj = $_SESSION['user'];
    }
    
    http_response_code(200);
    echo json_encode($userObj);
    