<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        require_once "../../config/connection.php";
        require_once "../functions.php";

        if(isset($_POST['articleId'])){
            $id = $_POST['articleId'];
            $string = "article";
        }else{
            $id = $_POST['userId'];
            $string = "user";
        }


        if (delete_from_cart($id, $string)) {
            http_response_code(200);
            echo json_encode("Uspesno obrisano!");
        }
        else {
            http_response_code(500);
            echo json_encode("Doslo je do grske, pokusajte malo kasnije.");
        }
    }
    else {
        http_response_code(400);
    }