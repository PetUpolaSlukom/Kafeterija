<?php
    header("Content-type: application/json");


    require_once "../../config/connection.php";
    require_once "../functions.php";

    $sort = isset($_GET["sort"]) ? $_GET["sort"] : "naziv asc";
    $category = isset($_GET["category"]) ? $_GET["category"] : false;
    $price = isset($_GET["price"]) ? $_GET["price"] : false;
    $search = isset($_GET["search"]) ? trim($_GET["search"]) : false;
    $page = isset($_GET["limit"]) ? $_GET["limit"] : 0;
    $pagination = 6;
    $stringQuery = "";
    $params_array = [];


    multi_value_filter($category, "id_kategorija");
    multi_value_filter($price, "cena");  

    function multi_value_filter($input, $column){
        global  $stringQuery, $params_array; 

        if($input){

            if ($column == "cena") {
                $priceString = "";
                $array = array_map("between", explode(", ",$input));

                $stringQuery .= createPriceString($array);
            }

            if($column == "id_kategorija") {
                $array = array_map("toInt", explode(",",$input));
                $q_marks = implode(",", array_fill(0, count($array), "?"));

                //whereOrAnd($whatAboutWhere);

                $stringQuery .= " AND $column IN (".$q_marks.")";
                $params_array = array_merge($params_array, $array);
            }
            
        }
    }

    function createPriceString($array)
    {
        $priceString = " AND (cena BETWEEN ";
        foreach ($array as $key => $value) {
            if ($key != 0) {
                $priceString .= " OR cena BETWEEN ";
            }
            $priceString .= "$value[0] AND $value[1]";
        }
        $priceString .= ")";
        return $priceString;
    }

    // array_map functions
    function between($priceArray)
    {
        return array_map("toInt", explode(" ",$priceArray));
    }
    function toInt($intString){
        return (int) $intString;
    }


    
    if($search != ""){
        global $stringQuery, $params_array; 
        
        $stringQuery .= " AND LOWER(naziv) LIKE ?";
        $params_array []= "%$search%";
    }
    if($sort != ""){
        global $stringQuery; 
        
        $type = explode(" ", $sort)[0];
        $direction = explode(" ", $sort)[1];

        if(in_array($type, ["naziv", "cena"]) && in_array($direction, ["asc", "desc"])){

            $stringQuery .= " ORDER BY $type $direction";
        }
    }
    // pagination
    $start_limit = ((int)$page) * (int)$pagination;
    $stringQueryLimit = $stringQuery . " LIMIT $start_limit, $pagination";
    

    $count = 0;

    try {


        $query = "SELECT * FROM pakovanje pak INNER JOIN proizvod pro ON pak.id_proizvod = pro.id_proizvod  WHERE pak.aktivan = 1 $stringQueryLimit";
        $select_query = $conn->prepare($query);
        $select_query->execute($params_array);
        $products = $select_query->fetchAll();
        


        $count_query = $conn->prepare("SELECT COUNT(*) AS count FROM pakovanje pak INNER JOIN proizvod pro ON pak.id_proizvod = pro.id_proizvod WHERE pak.aktivan = 1 $stringQuery");
        $count_query->execute($params_array);
        $result = $count_query->fetch();

        $count = $result->count;
        
        
        
        if(!$products){
            $products = [];
        }

        $response_code = 200;
    }
    catch(PDOException $ex){
        
        createLog(ERROR_LOG_FAJL, $ex->getMessage());
        $products = [];
        $response_code = 500;
    }

    echo json_encode([
        "pageCount"=>$count,
        "products"=>$products
    ]);
    http_response_code($response_code);