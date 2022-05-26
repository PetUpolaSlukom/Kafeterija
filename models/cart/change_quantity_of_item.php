<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        require_once "../../config/connection.php";
        require_once "../functions.php";

        $articleid = $_POST['articleId'];
        $newValue = $_POST['newValue'];

        if (quantity_change($articleid, $newValue)) {
            http_response_code(200);
            echo json_encode("Uspesno promenjena kolicina!");
        }
        else {
            http_response_code(500);
            echo json_encode("Doslo je do grske, pokusajte malo kasnije.");
        }
    }
    else {
        http_response_code(400);
    }