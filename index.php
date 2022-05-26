<?php
  require_once "config/connection.php";
  require_once "models/functions.php";

  $page = 'pocetna';
  if(isset($_GET['page'])){
    $page = $_GET['page'];
  }
  if(!file_exists("views/pages/$page.php")){
    header("Location: index.php?page=pocetna");
  }
  
  //ispis stranice
  require_once "views/fixed/head.php";
  require_once "views/fixed/nav.php";

  $infoPage = getPageInfo($page);

  if($infoPage):
?>


  <div class="naslovna bg-<?=$page?> bg d-flex flex-wrap justiffy-content-center col-12">
    <div class="container col-12 col-sm-10 d-flex flex-wrap justify-content-center">
      <div class="container-fuid float-center text-light col-10 bg-gray">
        <h1 class="col-12 text-center py-5 text-muted"><?=$infoPage[0]->naslov?></h1>
      </div>
    </div>
  </div>
  <?php
    if ($page == 'proizvod') {
      echo '<a href="index.php?page=proizvodi" class="text-center text-secondary">
              <h4 class="my-5">
                <i class="fas fa-long-arrow-alt-left mr-2"></i>
                Svi proizvodi
              </h4>
            </a>';
    }
  ?>


<?php 
  endif;

  require_once "views/pages/$page.php";
  require_once "views/fixed/footer.php";
  

