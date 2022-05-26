<?php

include_once "../../config/connection.php";

if (!isset($_POST["korpa-submit"])) {
    header("Location: ../../index.php?page=pocetna");
}
else {
    $regFullName = "/^[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}(\s[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}){0,2}$/";
    if(!preg_match($regFullName, $_POST["imePrezime"])){
        header("Location: ../../index.php?page=korpa&error=Neispravno%20ime%20i%20prezime.");
        die();
    }

    $regEmail = "/^[a-z]((\.|-|_)?[a-z0-9]){2,}@[a-z]((\.|-|_)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i";
    if(!preg_match($regEmail, $_POST["email"])){
        header("Location: ../../index.php?page=korpa&error=Neispravan%20email.");
        die();
    }

    $regAddress = "/^[\w\.]+(,?\s[\w\.]+){2,8}$/";   
    if(!preg_match($regAddress, $_POST["adresa"])){
        header("Location: ../../index.php?page=korpa&error=Neispravna%20adresa.");
        die();
    }

    $user = $_SESSION['user'];
    $userId = $user->id_korisnik;

    $fullName = $_POST["imePrezime"];
    $email = $_POST["email"];
    $address = $_POST["adresa"];
    $total_price = $_POST["hidden-medjuzbir"];
    

    try {
        $query = $conn->prepare("INSERT INTO porudzbina(id_korisnik, ime_prezime, kontakt_email, adresa, ukupna_cena) VALUES(?,?,?,?,?)");
        $result = $query->execute([$userId, $fullName, $email, $address, $total_price]);
        if ($result) {
            $message = "Uspešno realizovana porudzbina. Ocekujte isporuku u naredna 4 dana.";
            delete_from_cart($userId, "user");
            header("Location: ../../index.php?page=korpa&message=".$message);
        }
        else {
            $error = "Greška na serveru. Molimo pokušajte malo kasnije.";
            header("Location: ../../index.php?page=korpa&error=".$error);
        }
    } catch (PDOException $ex) {
        

        createLog(ERROR_LOG_FAJL, $ex->getMessage());
        $error = "Greška pri komunikaciji sa serverom, probajte kasnije ponovo.";
        header("Location: ../../index.php?page=korpa&error=".$error);
        die();
    }
}