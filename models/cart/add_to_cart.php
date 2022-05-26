<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        require_once "../../config/connection.php";
        require_once "../functions.php";

        $userId = $_POST['userId'];
        $productId = $_POST['productId'];

        
        if (add_to_cart($userId, $productId)) {
            http_response_code(200);
            echo json_encode("Uspesno dodato!");
        }
        else {
            http_response_code(500);
            echo json_encode("Doslo je do grske, pokusajte malo kasnije.");
        }
    }
    else {
        http_response_code(400);
    }