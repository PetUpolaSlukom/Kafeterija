<?php
    require_once "models/functions.php";

    if(isset($_GET['p_id'])){
        $p_id = $_GET['p_id'];
    }

    $product_obj = getProduct($p_id);
    $product = $product_obj[0];

?>

<div id="detalji-proizvoda" class="col-12 d-flex flex-wrap justify-content-between">
    <div class="col-12 d-flex flex-wrap justify-content-center">
    <?php
        if(isset($_GET["error"])){
            echo "<p class='alert alert-danger col-12 mt-3 text-center'>".$_GET["error"]."</p>";
        }
        if(isset($_GET["message"])){
            echo "<p class='alert alert-success col-12 mt-3 text-center'>".$_GET["message"]."</p>";
        }
    ?>
        <div class="col-10 col-md-7 col-lg-5 d-flex justify-content-center mt-4">
            <img src="assets/img/<?=$product->slika?>" alt="<?=$product->naziv?>" class="img-fluid col-12">
        </div>
        <div class="col-10 col-md-5 col-lg-5 mt-4">
            <div class="col-10">
            <?php
                if (isset($_SESSION['user'])):
            ?>
                <?php
                    if ($user->id_uloga == 1):
                ?>
                    <form name="kontakt-form" id="kontakt-form" class="col-12" method="POST" action="models/admin/change_product.php" enctype="multipart/form-data">
                        <h2 class="col-12 mb-5">Izmeni proizvod</h2>
                        <div class="container-fluid col-12">
                            <div>
                                <label class="mb-0 text-danger">Nova slika: </label>
                                <input type="file" name="slika-proizvod" class="mb-3" />
                            </div>
                            <div>
                                <label class="mb-0 text-danger">Novi naziv: </label>
                                <input type="text" name="naziv" id="naziv" class="mb-3" value="<?=$product->naziv?>">
                            </div>
                            <div>
                                <label class="mb-0 text-danger">Novi opis: </label>
                                <textarea name="opis" id="opis" cols="22" maxlength="200" rows="6" class="container mb-3"><?=$product->opis?></textarea><br>
                            </div>
                            <div class="mb-3">
                                <label class="mb-0 text-danger">Količina: </label>
                                <h6 class="text-muted d-inline"><?=$product->kolicina?> <?=stringMernaJedinica($product->merna_jedinica)?></h6>
                            </div>
                            <div>
                                <label class="mb-0 text-danger">Nova cena: </label>
                                <input type="number" name="cena" id="cena" class="col-7 mb-3" value="<?=$product->cena?>"> RSD
                            </div>
                            <div class="d-flex justify-content-around flex-wrap py-1">
                                <input type="submit" id="proizvod-izmena-button" name="proizvod-izmena-button" class="btn btn-success border-none mt-3 mx-0" value="Sačuvaj izmene">
                                <input type="button" id="proizvod-ukloni-button" data-id="<?=$p_id?>" name="proizvod-ukloni-button" class="btn btn-danger border-none mt-3 col-12" value="Ukloni iz ponude">
                            </div> 
                            <p id="proizvod-change-info" class='alert col-9 mt-3 mx-auto'></p>

                            </div>
                        </div>
                        <input type="hidden" name="hidden-id-pa" value="<?=$p_id?>">
                        <input type="hidden" name="hidden-id-pr" value="<?=$product->id_proizvod?>">
                    </form>
                    
                <?php
                    endif;
                ?>
                
            <?php
                else:
            ?>  
                <h5 class="text-muted mb-5"><?=$product->naziv_kategorije?></h5>
                <h2 class="text-dark mb-5"><?=$product->naziv?></h2>
                <p class="text-muted mb-5"><?=$product->opis?></p>
                <h6 class="text-muted mb-1"><?=$product->kolicina?> <?=stringMernaJedinica($product->merna_jedinica)?></h6>
                <h4 class="card-text font-weight-bold text-dark mb-5"><?=$product->cena?>,00 rsd</h4>
                <a href="#" class="btn btn-success border-none m3-5 dodajUKorpu">Dodaj u korpu</a>
            <?php
                endif;
            ?>
                
            </div>
        </div>
    </div>
</div>