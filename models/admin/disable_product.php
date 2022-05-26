<?php
    require_once "../../config/connection.php";
    require_once "../functions.php";

    if (!isset($_POST["id"])) {
        header("Location: index.php?page=home");
        die();
    }

    $id = $_POST["id"];

    if (disable_product($id)) {
        http_response_code(200);
        echo json_encode(true);
    }
    else {
        http_response_code(500);
        echo json_encode(falsw);
    }