<?php
        if(!($user && $user->id_uloga == 1)){
            header("Location: index.php?page=home");
            die();
        }

        require_once "models/functions.php";
?>
<div id="statistika" class="col-10 d-flex flex-wrap justify-content-between mx-auto">
    <h1 class="col-12 text-center">Statistika pristupa stranicama</h1>
    <?php 
        $statistic = get_statistic("all");
        $statistic_today = get_statistic("today");
    ?>
    <div class="col-12 col-md-5 mt-5">
        <h3>Ukupna statistika:</h3>
        <table class="table col-12">
            <thead>
                <tr class="table-success">
                    <th scope="col">#</th>
                    <th scope="col">Stranica</th>
                    <th scope="col">Posećenost</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;
                    foreach($statistic["page_percentages"] as $key=>$value):
                ?>
                    <tr>
                        <th scope="row"><?=$i++?></th>
                        <td><?=$key?></td>
                        <td><?=$value?>%</td>
                    </tr>                    
                <?php
                    endforeach;
                    $i = 1;
                ?>
            </tbody>
        </table>
        <div class="col-12 mb-5 mt-5">
            <h4 class="col-12">Eksportuj ukupnu statistiku u Excel</h4>
            <a href="models/admin/export_to_excel.php" id="expExcel" class="mx-auto col-12 btn btn-success">Export u Excel</a>
        </div>
    </div>
    <div class="col-12 col-md-5 mt-5">
        <h3>Statistika za danasnji pristup:</h3>
        <table class="table col-12">
            <thead>
                <tr class="table-success">
                    <th scope="col">#</th>
                    <th scope="col">Stranica</th>
                    <th scope="col">Posećenost</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;
                    foreach($statistic_today["page_percentages"] as $key=>$value):
                ?>
                    <tr>
                        <th scope="row"><?=$i++?></th>
                        <td><?=$key?></td>
                        <td><?=$value?>%</td>
                    </tr>
                <?php
                    endforeach;
                    $i = 1;
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-12 my-3 border-bottom py-5">
        <h3 class="col-12 text-center">Korisnici koji su danas posetili sajt (<?=count($statistic_today["users"])?>):
            <?php
                $count = count($statistic_today["users"]);
                $html = "";
                for ($i=0; $i < $count; $i++) { 
                    ($i != $count-1) ? $html .= $statistic_today["users"][$i].", " : $html .= $statistic_today["users"][$i];
                }
                echo $html;
            ?>
        </h3>
    </div>
    <div class="col-12 my-3 border-bottom py-5">
        <h3 class="col-12 text-center">Poruke:</h3>
        <?php
            $messages = getSelectQuery("SELECT * FROM poruka");
        ?>
            <table class="table col-6 mx-auto">
                <thead>
                    <tr class="table-success">
                        <th scope="col">#</th>
                        <th scope="col">Korisnik</th>
                        <th scope="col">Email</th>
                        <th scope="col">Tekst poruke</th>
                        <th scope="col">Vreme slanja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 1;
                        foreach($messages as $mes):
                    ?>
                        <tr>
                            <th scope="row"><?=$i++?></th>
                            <td><?=$mes->ime_prezime_korisnika?></td>
                            <td><?=$mes->email?></td>
                            <td><?=$mes->tekst?></td>
                            <td><?=$mes->vreme?></td>
                        </tr>
                    <?php
                        endforeach;
                        $i = 1;
                    ?>
                </tbody>
            </table>
    </div>
    <div class="col-12 my-3 border-bottom py-5">
        <h3 class="col-12 text-center">Korisnici:</h3>
        <?php
            $users = getSelectQuery("SELECT * FROM korisnik k INNER JOIN uloga u WHERE u.id_uloga = k.id_uloga");
            //var_dump($users);
        ?>
            <table class="table col-6 mx-auto">
                <thead>
                    <tr class="table-success">
                        <th scope="col">#</th>
                        <th scope="col">Uloga</th>
                        <th scope="col">Ime</th>
                        <th scope="col">Prezime</th>
                        <th scope="col">Email</th>
                        <th scope="col">Upravljaj</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 1;
                        foreach($users as $user):
                    ?>
                        <tr>
                            <th scope="row"><?=$i++?></th>
                            <td><?=$user->naziv?></td>
                            <td><?=$user->ime?></td>
                            <td><?=$user->prezime?></td>
                            <td><?=$user->email?></td>
                            <?php
                            if ($user->id_uloga == "1") {
                                echo '<td>Admin</td>';
                            }
                            else {
                                if ($user->aktivan == 1) {
                                    echo '<td><a href="#" data-id="'.$user->id_korisnik.'" class=" col-12 text-danger deaktivirajNalog">Deaktiviraj</a></td>';
                                }
                                else {
                                echo '<td><a href="#" data-id="'.$user->id_korisnik.'" class=" col-12 text-success aktivirajNalog">Aktiviraj</a></td>';
                                }
                            }
                            ?>
                        </tr>
                    <?php
                        endforeach;
                        $i = 1;
                    ?>
                </tbody>
            </table>
    </div>
</div>