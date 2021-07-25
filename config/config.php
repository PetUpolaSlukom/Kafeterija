<?php

// Osnovna podesavanja
define("BASE_URL", "http://localhost/sajt_pphp");
define("ABSOLUTE_PATH", $_SERVER["DOCUMENT_ROOT"]."/sajt_pphp");

// Ostala podesavanja
define("ENV_FAJL", ABSOLUTE_PATH."/config/.env");
define("LOG_FAJL", ABSOLUTE_PATH."/data/log.txt");
define("SEPARTOR", "&");

// Podesavanja za bazu
define("SERVER", env("SERVER"));
define("DATABASE", env("DBNAME"));
define("USERNAME", env("USERNAME"));
define("PASSWORD", env("PASSWORD"));




function env($naziv){
    // $podaci = explode("\n",file_get_contents(BASE_URL."/config/.env"));
    $open = fopen(ENV_FAJL, "r");
    $podaci = file(ENV_FAJL);
    $vrednost = "";
    foreach($podaci as $key=>$value){
        $konfig = explode("=", $value);
        if($konfig[0]==$naziv){
            $vrednost = trim($konfig[1]); // trim() zbog \n
        }
    }
    return $vrednost;
}
