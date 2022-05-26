<?php
    header("Content-type: application/json");

    require_once "../../config/connection.php";
    require_once "../functions.php";

    try {
        
        $products = getSelectQuery("SELECT * FROM pakovanje pak INNER JOIN proizvod pro ON pak.id_proizvod = pro.id_proizvod WHERE uFokusu =1");


        if(!$products){
            $products = [];
        }

        $response_code = 200;

    } catch (PDOException $ex) {
        
        createLog(ERROR_LOG_FAJL, $ex->getMessage());
        $products = [];
        $response_code = 500;
    } 
    
    echo json_encode(["products"=>$products]);
    http_response_code($response_code);