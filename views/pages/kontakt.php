<div class="d-flex justify-content-around flex-wrap py-5 m-0 col-12">
    <div id="kontaktInfo" class="col-8 col-md-4 py-5">
        <h2 class="col-12 mb-5">Kontakt informacije</h2>
        <div class="col-12">
            <ul id="kontaktInfoLista" class="p-0">
                <li class="py-3 d-flex align-items-center">
                    <span class="mr-4 fas fa-map-marked-alt text-success"></span> Cara Dušana 16, Beograd
                </li>
                <li class="py-3 d-flex align-items-center">
                    <span class="mr-4 fas fa-map-marked-alt text-success"></span> Milana Rakića 77, Beograd
                </li>
                <li class="py-3 d-flex align-items-center">
                    <span class="mr-4 far fa-clock text-success"></span>Svim danima od 10:00 do 21:00
                </li>
                <li class="py-3 d-flex align-items-center">
                    <span class="fas fa-phone mr-3 text-success"></span><a class="text-success td-u" href="tel:+381694301312">+381 69 420 1312</a>
                </li>
                <li class="py-3 d-flex align-items-center">
                    <span class="fas fa-envelope mr-3 text-success"></span><a class="text-success td-u" href="mailto:djordje.minic.135.19@ict.edu.rs">kafeterija.centar@gmail.com</a>
                </li>
            </ul>   
        </div>
    </div>
    <form name="kontakt-form" id="kontakt-form" class="col-8 col-md-4 py-5" method="POST" action="models/contact/contact.php">
        <h2 class="col-12 mb-5">Pošalji nam poruku</h2>
        <div class="container-fluid col-12">
            <div>
                <input type="text" name="name" id="name" class="container border-bottom-green  py-3" placeholder="Ime i prezime">
            </div>
            <div>
                <input type="email" name="email" id="email" class="container border-bottom-green py-3 mt-2" placeholder="Email">
            </div>
            <div>
                <textarea name="text" id="text" cols="22" maxlength="200" rows="4" class="container border-bottom-green py-3 mt-2" placeholder="Tekst poruke..."></textarea>
            </div>
            <div class="d-flex align-items-center flex-column py-3">
                <input type="submit" id="kontakt-button" name="kontakt-button" class="btn btn-success" value="Pošalji">
            </div>      
        </div>
        <p class="form-error text-danger"></p>
        <?php
            if(isset($_GET["error"])){
                echo "<p class='alert alert-danger col-9 mt-3 mx-auto'>".$_GET["error"]."</p>";
            }
            if(isset($_GET["message"])){
                echo "<p class='alert alert-info col-8 mt-3 mx-auto'>".$_GET["message"]."</p>";
            }
        ?>
    </form>
</div>