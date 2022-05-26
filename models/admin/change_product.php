<?php

include_once "../../config/connection.php";

if(!($user && $user->id_uloga == 1)){
    header("Location: ../../index.php?page=pocetna");
    die();
}

if (!isset($_POST['proizvod-izmena-button'])) {
    header("Location: ../../index.php?page=pocetna");
}

    //slika
    $tmp_name = $_FILES["slika-proizvod"]["tmp_name"];
    $filename = $_FILES["slika-proizvod"]["name"];
    $size = $_FILES["slika-proizvod"]["size"];

    $idPa = $_POST['hidden-id-pa'];
    $idPr = $_POST['hidden-id-pr'];
    $title =  $_POST["naziv"];
    $description = $_POST["opis"];
    $_POST["cena"] == "" ? $price = 0 : $price = $_POST["cena"];

    if($filename == ""){
        $error = "Greška pri unosu Fajla.";
        header("Location: ../../index.php?page=proizvod&p_id=".$idPa."&error=".$error);
        die();
    }
    if($size > 3 * 1024 * 1024){
        $error = "Slika mora biti manja od 3MB.";
        header("Location: ../../index.php?page=proizvod&p_id=".$idPa."&error=".$error);
        die();
    }
    
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $extension_allowed = ["jpg", "jpeg", "png"];
    if(!in_array($ext, $extension_allowed)){
        $error = "Slika mora biti nekog od sledecih formata: jpg, jpeg, png";
        header("Location: ../../index.php?page=proizvod&p_id=".$idPa."&error=".$error);
        die();
    }

    $new_filename = create_image($tmp_name, $ext);
    $new_thumbnail_filename = create_thumbnail($tmp_name, $ext);

    if($new_filename && $new_thumbnail_filename){

        try {
            $update = $conn->prepare("UPDATE pakovanje SET slika = ?, slika_umanjena = ? WHERE id_pakovanje = ?");
            $result = $update->execute([$new_filename, $new_thumbnail_filename, $idPa]);
    
            
            if($result){
                if (change_product($idPa, $idPr, $title, $description, $price)) {
                    $message = "Proizvod je uspešno ažuriran.";
                    header("Location: ../../index.php?page=proizvod&p_id=".$idPa."&message=".$message);
                }
                else {
                    $error = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
                    header("Location: ../../index.php?page=proizvod&p_id=".$idPa."&error=".$error);
                    die();
                }
            }
            else {
                $error = "Greška pri azuriranju slike.";
                header("Location: ../../index.php?page=proizvod&p_id=".$idPa."&error=".$error);
                die();
            }
            
            
        }
        catch(PDOException $ex){
            $error = "Failed to change the cover.";
            create_log(ERROR_LOG_FILE, $ex->getMessage());
            header("Location: ../../index.php?page=admin&panel=covers&error=$error");
        }
    }
    else {
        $error = "Greška pri kreiranju slike.";
        header("Location: ../../index.php?page=proizvod&p_id=".$idPa."&error=".$error);
    }
    
    
    
    
