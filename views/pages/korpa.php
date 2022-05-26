<?php
    if (!isset($_SESSION['user'])) {
        header ("Location: index.php?page=prijava&error=Morate biti ulogovani da bi pristupili korpi.");
    }
?>
<?php
        if(isset($_GET["error"])){
            echo "<p class='alert alert-danger col-9 mt-3 mx-auto'>".$_GET["error"]."</p>";
        }
        if(isset($_GET["message"])){
            echo "<p class='alert alert-success col-8 mt-3 mx-auto'>".$_GET["message"]."</p>";
        }
    ?>
<div class="col-12" id="div-korpa">
        <div class="container text-white mt-5 mb-5 p-0" id="korpaSpisak">
        </div>
    <div id="obrisiSve" class="container py-3 mt-5">
        <button type="button" id="buttonObrisiKorpu" class="btn btn-danger float-left" value="Obriši sve iz korpe">
            <i class="fas fa-trash-alt align-center text-light"></i> Obriši sve iz korpe
        </button>
    </div>
    <div id="medjuzbir-placanje" class="container-fluid">
        <div class="col-12 p-50-0 d-flex flex-wrap justify-content-around">
            <div class="col-md-5 col-12 container p-50-0">
                <h2 class="mb-5">VAŠ UKUPNI TROŠAK</h2> 
                <table class="table table-hover table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th scope="col" class=" text-light"> </th>
                            <th scope="col" class=" text-light"> </th>
                        </tr>
                    </thead>
                    <tbody id="tbodyNarucivanje">
                        <tr>
                            <td class="text-dark text-center">Međuzbir</td>
                            <td id="tdMedjuzbir" class="text-dark text-right"></td>
                        </tr>
                        <tr>
                            <td class="text-dark text-center">Dostava*</td>
                            <td id="tdCenaDostave"  class="text-dark text-right"></td>
                        </tr>
                        <tr>
                            <td class="cena text-center">Ukupno</td>
                            <td  id="tdUkupano" class="cena text-right"></td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-secondary">*Za porudzbine preko 1500 dinara dostava je besplatna.</p>
            </div>
            <div class="col-md-5 col-12 container p-50-0">
                <h2>PODACI ZA PLAĆANJE</h2> 
                <form name="korpa" id="korpa-forma" class="col-12 pb-5" method="POST" action="models/cart/order.php">
                    <div class="container-fluid col-12">
                        <div>
                            <label for="adresa" class="text-dark mt-2 d-block">Ime i prezime primaoca</label>
                            <input type="text" name="imePrezime" id="korpa-imePrezime" class="container brd-none py-3 text-dark" placeholder="Npr: Jelena Berber">
                        </div>
                        <div>
                            <label for="adresa" class="text-dark mt-2 d-block">Kontakt Email</label>
                            <input type="email" name="email" id="korpa-email" class="container brd-none py-3 text-dark" placeholder="Npr: moj.email@gmail.com">
                        </div>
                        <div>
                            <label for="adresa" class="text-dark mt-2 d-block">Adresa i Grad</label>
                            <input type="text" id="korpa-adresa" 
                            name="adresa" class="container brd-none py-3 text-dark" placeholder="Npr: Nikole Tesle 16, Nova Varoš">
                        </div>
                        <div class="d-flex align-items-center flex-column py-3 mt-5">
                            <input type="submit" name="korpa-submit" id="korpa-submit" class="btn btn-success" value="Završi porudžbinu">
                        </div>
                        <input type="hidden" id="hidden-medjuzbir" name="hidden-medjuzbir">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
    
</div>