<div class="col-12 pl-0 d-flex justify-content-around flex-wrap naslovna bg-logovanje">
    <form name="prijava" id="prijava-forma" class="col-8 col-md-4 pt-5 pb-3 bg-gray" method="POST" action="models/user/login.php">
        <h2 class="col-12 mb-5 text-center">Prijavi se</h2>
        <div class="container-fluid col-12">
            <div>
                <input type="email" name="email" id="email" class="container border-bottom-green  py-3" placeholder="Email">
            </div>
            <div>
                <input type="password" name="password" id="password" class="container border-bottom-green py-3 mt-2" placeholder="Lozinka">
            </div>
            <div class="d-flex align-items-center flex-column py-3">
                <input type="submit" id="login-button" name="login-button" class="btn btn-success" value="Prijavi se">
            </div>      
        </div>
        <p class='text-center  py-3'>Nemate nalog?<a href="index.php?page=registracija" class='text-success td-u'> Registrujte se.</a></p>
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