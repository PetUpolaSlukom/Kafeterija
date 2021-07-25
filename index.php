<?php
  require_once "config/connection.php";
  
  include "views/fixed/head.php";
  include "views/fixed/nav.php";


  if(!isset($_GET['page'])){
     include "views/pocetna.php";
  }
  else {
    switch($_GET['page']){
      case 'zadatak1':
        include "views/tabela-adresar.php";
        break;
      case 'zadatak1-forma':
          include "views/forma-adresar.php";
          break;
      case 'zadatak2':
        include "views/tabela-kategorije.php";
        break;
      default: 
        include "views/pocetna.php";
        break;
    }
  }



  include "views/fixed/footer.php";

