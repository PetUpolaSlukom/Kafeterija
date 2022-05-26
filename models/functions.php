<?php

function checkSession(){

    session_start();
    global $user;
    if(isset($_SESSION["user"])){
        $user = $_SESSION["user"];
    }

}

function getPageInfo($page)
{
    $stringPage = $page;
    try {
        
        global $conn;

        $query = "SELECT * FROM stranice WHERE naziv_stranice = :pageName ";
        $stat = $conn->prepare($query);
        $stat->bindParam(":pageName", $stringPage);
        $stat->execute();

        $rez = $stat->fetchAll();

        return $rez;
    } catch (PDOException $ex) {
        createLog(ERROR_LOG_FAJL, $ex->getMessage());
        return false;
    }
}

function createLog($filename, $message){
    global $user;

    $_user = "Guest";
    $role = "";
    if($user){
        $_user = $user->ime;
    }
    
    $url = $_SERVER["PHP_SELF"];
    $ip = $_SERVER["REMOTE_ADDR"];
    $time = date("Y-m-d H:i:s");
    if(isset($_GET["page"])) {
        $page = $_GET["page"];
    } 
    else{
        $page = "no page";        
    } 

    $line = "$time\n$_user\n$ip\n$url\n$page\n$message\n_______________________________________\n\n";

    file_put_contents($filename, $line, FILE_APPEND);
}

function getSelectQuery($string, $fetchString = "fetchAll")
{
    if ($fetchString == "fetch") {
        try {

            global $conn;
    
            $query = $string;
            return $conn->query($query)->fetch();
    
        } catch (PDOException $ex) {
            createLog(ERROR_LOG_FAJL, $ex->getMessage());
            return false;
        }
        
    }
    else {
        try {

            global $conn;
    
            $query = $string;
            return $conn->query($query)->fetchAll();
    
        } catch (PDOException $ex) {
            createLog(ERROR_LOG_FAJL, $ex->getMessage());
            return false;
        }
    }
    
}

function getProduct($id)
{
    global $conn;

    try {
        $result = $conn->prepare("SELECT (SELECT naziv FROM kategorija k WHERE k.id_kategorija = pr.id_kategorija) AS naziv_kategorije, pr.id_proizvod, pr.naziv, pr.opis, pa.kolicina, pa.merna_jedinica, pa.cena, pa.slika FROM proizvod pr INNER JOIN pakovanje pa ON pa.id_proizvod = pr.id_proizvod WHERE pa.id_pakovanje = ?");
        $result->execute([$id]);
        return $result->fetchAll();

    } catch (PDOException $ex) {
        createLog(ERROR_LOG_FAJL, $ex->getMessage());
        return false;
    }
}

// product

function disable_product($id)
{
    global $conn;

    try {
        $disable = $conn->prepare("UPDATE pakovanje SET aktivan = 0 WHERE id_pakovanje = ?");
        $result = $disable->execute([$id]);
        
        return $result;
    } catch (PDOException $ex) {
        createLog(ERROR_LOG_FAJL, $ex->getMessage());
        return false;
    }
}

function change_product($idPa, $idPr, $title, $description, $price)
{
    global $conn;

    try {
        $change1 = $conn->prepare("UPDATE pakovanje SET cena = ? WHERE id_pakovanje = ?");
        $result1 = $change1->execute([$price, $idPa]);

        $change2 = $conn->prepare("UPDATE proizvod SET naziv = ?, opis = ? WHERE id_proizvod = ?");
        $result2 = $change2->execute([$title, $description, $idPr]);
        
        return $result1 && $result2;
    } catch (PDOException $ex) {
        createLog(ERROR_LOG_FAJL, $ex->getMessage());
        return false;
    }
}

function stringMernaJedinica($dbString)
{
    if ($dbString == "gram") {
        return "g";
    }
    elseif($dbString == "kilogram") {
        return "kg";
    }
    elseif($dbString == "mililitar") {
        return "ml";
    }
    return $dbString;

}

// pagination

define ("OFFSET", 6);
function getProductsWidthLimit($limit = 0)
{
    global $conn;

    $lim = $limit * 6;

    $query = "SELECT * FROM pakovanje pak INNER JOIN proizvod pro ON pak.id_proizvod = pro.id_proizvod LIMIT :limit, :offset";
    $select = $conn->prepare($query);

    $limit = ((int) $limit * OFFSET);
    $select->bindParam(":limit", $limit, PDO::PARAM_INT);

    $offset = OFFSET;
    $select->bindParam(":offset", $offset, PDO::PARAM_INT);

    $select->execute();
    $products = $select->fetchAll();

    return $products;
}

function countPages()
{
    $countProducts = getSelectQuery("SELECT COUNT(*) as broj FROM pakovanje", "fetch");
    $countPages = ceil($countProducts->broj / OFFSET);
    
    return $countPages;
}

//cart

function add_to_cart($userId, $productId)
{
    global $conn;

    try {

        $productInCart = isInCart($userId, $productId);
        if ($productInCart) {
            $quantity = $productInCart[0]->kolicina + 1;
            $update = $conn->prepare("UPDATE stavka_korpe SET kolicina = ? WHERE id_stavka_korpe = ?");
            $result = $update->execute([$quantity, $productInCart[0]->id_stavka_korpe]);
        }
        else {
            $quantity = 1;
            $exec = $conn->prepare("INSERT INTO stavka_korpe(id_korisnik, id_pakovanje, kolicina) VALUES (?, ?, ?)");
            $result = $exec->execute([$userId, $productId, $quantity]);
        }

        return $result;
    } catch (PDOException $ex) {
        createLog(ERROR_LOG_FAJL, $ex->getMessage());
        return false;
    }
}

function isInCart($userId, $productId) {
    global $conn;

    $select = $conn->prepare("SELECT * FROM stavka_korpe WHERE id_korisnik = ? AND id_pakovanje = ?");
    $select->execute([$userId, $productId]);
    return $select->fetchAll();
}
   
function getCartQuantity($userId) {
    global $conn;

    $select = $conn->prepare("SELECT IFNULL(SUM(kolicina), 0) as kolicina FROM stavka_korpe WHERE id_korisnik = ?");
    $select->execute([$userId]);
    $result = $select->fetch();
    $quantity = $result->kolicina;

    return $quantity;
}

function getCartProducts($userId)
{
    global $conn;

    $select = $conn->prepare("SELECT sk.id_stavka_korpe, sk.kolicina, pr.naziv, pa.cena, pa.slika_umanjena as slika_umanjena FROM stavka_korpe sk INNER JOIN pakovanje pa ON sk.id_pakovanje = pa.id_pakovanje INNER JOIN proizvod pr ON pr.id_proizvod = pa.id_proizvod WHERE id_korisnik = ?");
    $select->execute([$userId]);
    $result = $select->fetchAll();

    return $result;
}

function delete_from_cart($id, $string)
{
    global $conn;

    try {
        $string == "user" ? $query = "DELETE FROM stavka_korpe WHERE id_korisnik = ?" : $query = "DELETE FROM stavka_korpe WHERE id_stavka_korpe = ?";

        $delete = $conn->prepare($query);
        $result = $delete->execute([$id]);
    
        return $result;
    } catch (PDOException $ex) {
        createLog(ERROR_LOG_FAJL, $ex->getMessage());
        return false;
    }
}

function quantity_change($articleid, $newValue)
{
    global $conn;

    try {
        
        $delete = $conn->prepare("UPDATE stavka_korpe SET kolicina = ? WHERE id_stavka_korpe = ?");
        $result = $delete->execute([$newValue, $articleid]);
    
        return $result;
    } catch (PDOException $ex) {
        createLog(ERROR_LOG_FAJL, $ex->getMessage());
        return false;
    }
}


// ADMIN

function get_statistic($today){

    ($today == "today") ? $today=true : $today=false;


    $page_stats = [];
    $users = [];
    $count = 0;

    $log_file = fopen(LOG_FAJL, "r");        
    if($log_file) {

        $size = filesize(LOG_FAJL);
        while (!feof($log_file)) {

            $line = stream_get_line($log_file, $size, "\n\n");
            if(!trim($line)){
                continue;
            }

            list($timestamp, $username, $ip, $url, $target) = explode("\n", $line);

            if($target == "no page"){
                continue;
            }
            if($today){
                $min = strtotime("today 00:00:01");
                if(strtotime($timestamp) < $min){
                    continue;
                }
                
                if($username != "Guest" && !in_array($username, $users)){
                    $users []= $username;
                }
            }
            $count++;

            if(isset($page_stats[$target])){
                $page_stats[$target]++;

            }
            else {
                $page_stats[$target] = 1;
            }
        }
        fclose($log_file);
        arsort($page_stats);
    }
    
    return ["page_percentages"=>get_page_view_percentages($page_stats, $count), "users"=>$users];
}

function get_page_view_percentages($page_stats, $total){
    if($total == 0){
        return $page_stats;
    }
    foreach($page_stats as $i=>$value){
        $page_stats[$i] = number_format($value / $total * 100, 1);
    }

    return $page_stats;
}

function get_messages(){
    global $conn;

    try {
        return getSelectQuery("SELECT * FROM poruka");

    } catch (PDOException $ex) {
        createLog(ERROR_LOG_FAJL, $ex->getMessage());
        return false;
    }
}

function disable_user($id, $action)
{
    global $conn;

    try {
        $action == "deact" ? $a = 0 : $a = 1;

        $disable = $conn->prepare("UPDATE korisnik SET aktivan = ? WHERE id_korisnik = ?");
        $result = $disable->execute([$a, $id]);
        
        return $result;
    } catch (PDOException $ex) {
        createLog(ERROR_LOG_FAJL, $ex->getMessage());
        return false;
    }
}

//image code 

function get_image_resource($path, $ext){

    switch($ext){
        case "jpg":
        case "jpeg":
            $image = imagecreatefromjpeg($path);
            break;
        case "png":
            $image = imagecreatefrompng($path);
            break;
        default:
            $image = false;
    }

    return $image;
}

function create_image_from_resource($image, $new_filename, $ext){

    
    $new_path = ABSOLUTE_PATH."/assets/img/$new_filename";
    switch($ext){
        case "jpg":
        case "jpeg":
            return imagejpeg($image, $new_path);
        case "png":
            return imagepng($image, $new_path);
    }
}

function create_image($tmp_filename, $ext){

    $image = get_image_resource($tmp_filename, $ext);

    $width = imagesx($image);
    $height = imagesy($image);

    if($width > 600){
        $new_height = 600 * $height / $width;
        $new_image = imagecreatetruecolor(600, $new_height);
        imagecopyresampled($new_image, $image, 0,0,0,0, 600, $new_height, $width, $height);
        imagedestroy($image);
        $image = $new_image;
    }
    elseif($height > 600){
        $new_width = 600 * $width / $height;
        $new_image = imagecreatetruecolor($new_width, 600);
        imagecopyresampled($new_image, $image, 0,0,0,0, $new_width, 600, $width, $height);
        
        imagedestroy($image);
        $image = $new_image;
    }

    $new_filename = str_replace(" ","", microtime()).".$ext";
    create_image_from_resource($image, $new_filename, $ext);
    imagedestroy($image);

    return $new_filename;

}

function create_thumbnail($tmp_filename, $ext){

    
    $image = get_image_resource($tmp_filename, $ext);

    
    $width = imagesx($image);
    $height = imagesy($image);

    if($width > 300){
        
        $new_height = 300 * $height / $width;
        $new_image = imagecreatetruecolor(300, $new_height);
        imagecopyresampled($new_image, $image, 0,0,0,0, 300, $new_height, $width, $height);

        imagedestroy($image);
        $image = $new_image;
    }

    $new_filename = str_replace(" ","", microtime())."-thumbnail.$ext";
    create_image_from_resource($image, $new_filename, $ext);
    imagedestroy($image);

    return $new_filename;
}