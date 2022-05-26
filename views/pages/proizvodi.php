<?php
    require_once "models/functions.php";

    $products = getProductsWidthLimit();
?>

<div id="proizvodi" class="min-vh-100 container-fluid m-0-auto p-50-0 d-flex justify-content-around">
        <div class="col-xl-10">
            <div class="col-12">
                <div class="col-12 col-md-10 float-right ">
                    <div class="col-md-5 col-12 float-right pl-0">
                        <h2>SORTIRANJE</h2>
                        <select class="custom-select mb-5 border-success" id="sort">
                            <option value="naziv asc" selected="">Po nazivu A-Z</option>
                            <option value="naziv desc">Po nazivu Z-A</option>
                            <option value="cena desc">Prvo najskuplje</option>
                            <option value="cena asc">Prvo najjeftinije</option>
                        </select>
                    </div>
                    <div class="col-md-5 col-12 float-right pl-0">
                        <h2>PRETRAGA</h2>
                        <ul class="list-group mb-5 col-12 p-0" >
                            <li class="list-group-item border-success">
                                <input type="search" class="border-0 col-12" id="search" name="pretraga" placeholder="Pretraži po nazivu...">
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row col-12">
                    <div class="col-md-3"> 
                        <h2>KATEGORIJE</h2>
                        <ul class="list-group mb-5 col-12 p-0" id="kategorije">
                            
                            <?php
                        
                                $kategorije = getSelectQuery("SELECT * FROM kategorija");
                                foreach ($kategorije as $key => $kategorija) :
                            ?>
                                <li class="list-group-item border border-0">
                                    <input type="checkbox" value="<?=$kategorija->id_kategorija?>" class="" name="kategorije"/> <?=$kategorija->naziv?>
                                </li>
                            <?php
                                endforeach;
                             ?>
                        </ul>
                   
                    <!--CENA-->
                        <h2>CENA</h2>
                        <ul class="list-group mb-5 col-12 p-0" id="cena">
                            
                            <li class="list-group-item border border-0">
                                <input type="checkbox" value="0 500" class="zanr" name="zanrovi"/> 0 - 500 
                            </li>
                            <li class="list-group-item border border-0">
                                <input type="checkbox" value="500 1000" class="zanr" name="zanrovi"/> 500 - 1000 
                            </li>
                            <li class="list-group-item border border-0">
                                <input type="checkbox" value="1000 2000" class="zanr" name="zanrovi"/> 1000 - 2000 
                            </li>
                            <li class="list-group-item border border-0">
                                <input type="checkbox" value="2000 5000" class="zanr" name="zanrovi"/> 2000  - 5000 
                            </li>
                        </ul>
                    </div>

                    <!--ISPIS PROIZVODA-->
                    <div class="col-md-9 p-0 ">
                        <div id="div-proizvodi" class="p-0 container-fluid col-12 d-flex flex-wrap justify-content-start">
                        
                        </div>
                        <!-- pagination -->
                        <?php
                        
                            $brStranica = countPages();
                        ?>
                        <div id="paginacija" class="row">
                            <div class="col-12 d-flex justify-content-around">
                                <ul id="pagination" class="pagination">
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>