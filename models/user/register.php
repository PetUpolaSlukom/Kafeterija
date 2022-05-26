<?php

include_once "../../config/connection.php";

if (!isset($_POST["register-button"])) {
    header("Location: ../../index.php?page=pocetna");
}
else {
    $regName = "/^[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}$/";
    if(!preg_match($regName, $_POST["firstName"])){
        header("Location: ../../index.php?page=registracija&error=Neispravno%20ime.");
        die();
    }
    if(!preg_match($regName, $_POST["lastName"])){
        header("Location: ../../index.php?page=registracija&error=Neispravno%20prezime.");
        die();
    }

    $regEmail = "/^[a-z]((\.|-|_)?[a-z0-9]){2,}@[a-z]((\.|-|_)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i";
    if(!preg_match($regEmail, $_POST["email"])){
        header("Location: ../../index.php?page=registracija&error=Neispravan%20email.");
        die();
    }

    $regPasswd = "/^[A-z\d]{8,}$/";
    if(!preg_match($regPasswd, $_POST["password"])){
        header("Location: ../../index.php?page=registracija&error=Neispravna%20lozinka.");
        die();
    }

    $fName = $_POST["firstName"];
    $lName = $_POST["lastName"];
    $email = $_POST["email"];
    $password =  md5($_POST["password"]);
    
    try {
        
        $query = $conn->prepare("SELECT email FROM korisnik WHERE email = ?");
        $query->execute([$email]);
        $user = $query->fetch();

    } catch (PDOException $ex) {

        create_log(ERROR_LOG_FAJL, $ex->getMessage());
        $error = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
        header("Location: ../../index.php?page=registracija&error=".$error);
    }

    if ($user) {
        if($user->email == $email){

            $error = "Email je vec u upotrebi.";
            
            header("Location: ../../index.php?page=registracija&error=".$error);
        }
    }else {
        try {
            $query = $conn->prepare("INSERT INTO korisnik(ime, prezime, email, lozinka) VALUES(?,?,?,?)");
            $result = $query->execute([$fName, $lName, $email, $password]);
            if ($result) {
                $message = "Uspešna registracija! Sada se možete ulogovati.";
                header("Location: ../../index.php?page=prijava&message=".$message);
            }
            else {
                $error = "Greška na serveru. Molimo pokušajte malo kasnije.";
    
                header("Location: ../../index.php?page=registracija&error=".$error);
            }
        } catch (PDOException $ex) {
            

            create_log(ERROR_LOG_FAJL, $ex->getMessage());
            $error = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
            header("Location: ../../index.php?page=registracija&error=".$error);
            die();
        }
    }
}