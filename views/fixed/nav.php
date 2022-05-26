<nav class="navbar navbar-expand-sm navbar-dark d-flex justify-content-around">
    <div id="responsive-meni" class="w-100 position-absolute d-md-none">
        <ul id="responsive-meni-ul" class="navbarMenu-ul bg-light w-100 text-center">
            <li class="nav-item"><a href="index.php?page=pocetna" class="nav-link text-secondary">Početna</a></li>
            <li class="nav-item"><a href="index.php?page=proizvodi" class="nav-link text-secondary">Proizvodi</a></li>
            <li class="nav-item"><a href="index.php?page=kontakt" class="nav-link text-secondary">Kontakt</a></li>
            <li class="nav-item"><a href="index.php?page=autor" class="nav-link text-secondary">Autor</a></li>
        </ul>
    </div>
    <button class="navbar-toggler">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a href="index.php?page=pocetna" class="navbar-brand col-4 col-lg-3"><img src="assets/img/kafeterija_logo.png" class="img-fluid" alt="Kafeterija logo"/></a>
    <div class="collapse navbar-collapse nav-pills col-lg-4 col-xl-3 border-left border-right" id="navbarMenu">
        <ul id="nav-meni-ul" class="navbarMenu-ul navbar-nav container justify-content-around">
        <li class="nav-item"><a href="index.php?page=pocetna" class="nav-link text-secondary">Početna</a></li>
            <li class="nav-item"><a href="index.php?page=proizvodi" class="nav-link text-secondary">Proizvodi</a></li>
            <li class="nav-item"><a href="index.php?page=kontakt" class="nav-link text-secondary">Kontakt</a></li>
            <li class="nav-item"><a href="index.php?page=autor" class="nav-link text-secondary">Autor</a></li> 
        </ul> 
    </div>
    <a id="korpaLink"  href="index.php?page=korpa" class='bg-light text-dark'>
        <button type="button" id="korpa" class="btn btn-lg btn-white">
            <span class="fa fa-shopping-cart" aria-hidden="true"></span>
            <span class="cart-count ml-2">0</span>
        </button>
    </a>
</nav>
<div class="help"></div>
<div id="log-div" class="container col-11 border-top pt-2">
    <ul class="float-right d-flex justify-content-around">
        <?php
            if (isset($_SESSION["user"])) {
                $user = $_SESSION["user"];
            }
            if ($user):
        ?>

            <?php
                if ($user->id_uloga == 1):
            ?>
            <li><a href="index.php?page=admin" class="nav-link text-success">Admin</a></li>
            <?php
                else:
            ?> 
            <li><p class="nav-link text-success"><?=$user->ime?></p></li>
            <?php
                endif;
            ?>

            <li><a href="models/user/logout.php" class="nav-link text-secondary border-left">Odjavi se</a></li>
                
        <?php
            else:
        ?>  
        
        <li><a href="index.php?page=prijava" class="nav-link text-secondary">Prijavi se</a></li>
        <li><a href="index.php?page=registracija" class="nav-link text-secondary border-left">Registracija</a></li>

        <?php
            endif;
        ?>
    </ul>
</div>
