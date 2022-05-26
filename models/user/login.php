<?php

include_once "../../config/connection.php";

if (!isset($_POST['login-button'])) {
    header("Location: ../../index.php?page=pocetna");
}
else {
    $regEmail = "/^[a-z]((\.|-|_)?[a-z0-9]){2,}@[a-z]((\.|-|_)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i";
    if(!preg_match($regEmail, $_POST["email"])){
        header("Location: ../../index.php?page=prijava&error=Neispravan%20email.");
        die();
    }

    $regPasswd = "/^[A-z\d]{8,}$/";
    if(!preg_match($regPasswd, $_POST["password"])){
        header("Location: ../../index.php?page=prijava&error=Neispravna%20lozinka.");
        die();
    }

    $email = $_POST["email"];
    $password =  md5($_POST["password"]);


    try {
        
        $query = $conn->prepare("SELECT * FROM korisnik WHERE email = ?");
        $query->execute([$email]);
        $user = $query->fetch();

    } catch (PDOException $ex) {

        createLog(ERROR_LOG_FILE, $ex->getMessage());
        $error = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
        header("Location: ../../index.php?page=prijava&error=".$error);
    }


    if($user->lozinka == $password && $user ){
        
        $_SESSION["user"] = $user;
        header("Location: ../../index.php?page=pocetna");
    }
    else {
        $error = "Neispravna email adresa ili lozinka.";
        header("Location: ../../index.php?page=prijava&error=$error");
    }
    
}