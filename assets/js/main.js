window.onload = () => {

    //navbar-toggle
    $(document).on("click", ".navbar-toggler", function () {
        $("#responsive-meni")
            .css({
                "left": "0",
                "top": `${$(".navbar").height()}px`
            })
            .toggle("slow");
    });


    showCartQuantity();
    let url = window.location.pathname;
    if ($("#proizvodi").length) {
        loadProducts();
    }
    if ($("#div-pocetna").length) {
        loadFocusProducts();
    }
    if ($("#div-korpa").length) {
        loadCart();
    }


    function checkAdmin() {
        ajaxFunction("models/user/get_user.php", 'GET', function (data) {
            isAdmin(data.id_uloga);
        });
    }
    function isAdmin(id) {
        if (id == 1) {
            $(".dodajUKorpu")
                .removeClass("btn-success")
                .addClass("btn-danger")
                .addClass("d-none")
                .html("Ukloni iz ponude")
            $(".detaljnije-link")
                .html("Izmeni")
                .addClass("text-danger");
        }
    }

    $(".deaktivirajNalog").click(function (e) {
        e.preventDefault();
        let id = $(this).data('id');

        ajaxFunction("models/admin/disable_user.php", 'POST', function (data) {
            stilizujAktivnost(id);
        }, {
            id: id,
            action: "deact"
        });
        function stilizujAktivnost(id) {
            let td = $("table").find(`[data-id='${id}']`)
            td.removeClass("text-danger").addClass("text-success").html('Aktiviraj').addClass("aktivirajNalog");
        }
    })
    $(".aktivirajNalog").click(function (e) {
        e.preventDefault();
        let id = $(this).data('id');

        ajaxFunction("models/admin/disable_user.php", 'POST', function (data) {
            stilizujAktivnost(id);
        }, {
            id: id,
            action: "act"
        });
        function stilizujAktivnost(id) {
            let td = $("table").find(`[data-id='${id}']`)
            td.removeClass("text-success").addClass("text-danger").html('Deaktiviraj').addClass("deaktivirajNalog");
        }
    })

    // POCETNA page
    function loadFocusProducts() {
        try {
            let ajaxResponse = ajaxFunction('models/products/get_focus_products.php', 'get', function (data) {
                showFocusProducts(data.products);
                //console.log(data.products);
            });
        } catch (error) {

        }
    }
    function showFocusProducts(products) {
        let html = "";
        for (let p of products) {
            html += `
                <a href="index.php?page=proizvod&p_id=${p.id_pakovanje}" class="border-none text-dark">
                    <div class="card col-7 col-md-5 col-lg-2 p-0 mb-5">
                        <img class="card-img-top" src="assets/img/${p.slika_umanjena}" alt="${p.naziv}">
                        <div class="card-body text-center pl-0 pr-0">
                            <h5 class="card-title mb-1">${p.naziv}</h5>
                            <p class="text-muted">${p.kolicina} ${stringMernaJedinica(p.merna_jedinica)} </p>
                            <div class="dot col-12 text-center">●</div>
                            <p class="card-text font-weight-bold">${p.cena},00 rsd</p>
                            <div class="dot col-12 text-center">●</div>
                            <a href="index.php?page=proizvod&p_id=${p.id_pakovanje}" class="border-none text-success col-12 detaljnije-link"><u>Detaljnije</u></a>
                            <a href="" class="btn btn-success border-none mt-3 dodajUKorpu" data-id="${p.id_pakovanje}">Dodaj u korpu</a>
                        </div>
                    </div>
                </a>`;
        }
        $("#div-pocetna").html(html);
        checkAdmin();
    }

    //KORPA page

    $(document).on("click", ".dodajUKorpu", function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        addToCart(id);

    });

    function addToCart(id) {
        ajaxFunction("models/user/get_user.php", 'GET', function (data) {
            if (data) {
                var user = data;
                //console.log(data);
                ajaxFunction("models/cart/add_to_cart.php", 'POST', function (data) {
                    showCartQuantity();
                    return (data);
                }, {
                    userId: user.id_korisnik,
                    productId: id
                });
            }
            else {
                window.location.href = "index.php?page=prijava&error=Morate biti ulogovani da bi obavljali kupovinu.";
            }
        });
    }

    function showCartQuantity() {
        ajaxFunction('models/cart/cart_quantity.php', 'GET', function (data) {
            $('.cart-count').html(data);
        });
    }


    function loadCart() {
        ajaxFunction('models/cart/load_cart.php', 'GET', function (data) {
            //console.log(data);
            let medjuzbir = 0;
            let html = "";
            if (data.length) {
                let products = data;
                html += `
                            <table id="ispisKorpe" class="table table-hover table-striped">
                                <thead class="bg-dark">
                                    <tr>
                                        <th scope="col" class=" text-light"> </th>
                                        <th scope="col" class=" text-light"> </th>
                                        <th scope="col" class=" text-light">Proizvod</th>
                                        <th scope="col" class="  text-light">Cena</th>
                                        <th scope="col" class="  text-light">Kolicina</th>
                                        <th scope="col" class="  text-white">Medjuzbir</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    <tr>`;
                for (let p of products) {
                    html += `
                    <tr>
                        <th scope="row" class="align-middle text-dark"><button class="obrisiIzKorpe border-0" data-id="${p.id_stavka_korpe}" ><i  class="fas fa-trash-alt align-center"></i></button></th>
                        <td class="align-middle text-dark w-25"><img class="w-25" src="assets/img/${p.slika_umanjena}" alt="${p.naziv}"></td>
                        <td class="align-middle text-dark">${p.naziv}</td>
                        <td class="align-middle text-dark">${p.cena},00 RSD</td>
                        <td class="align-middle text-dark"><input class="pl-2 kolicina" data-id="${p.id_stavka_korpe}" type="number" min="1" max="99" step="1" value="${p.kolicina}"></td>
                        <td class="align-middle text-dark">${p.kolicina * p.cena},00 RSD</td>
                    </tr>`;

                    medjuzbir += p.kolicina * p.cena;
                }
                html += `</tbody>
                    </table>`;

                $("#obrisiSve, #medjuzbir-placanje").removeClass("d-none");

                totalPrice(medjuzbir);
                autofillTextboxCart();
            }
            else {
                html += `<div class='d-flex justify-content-center mx-0'>
                <img src="assets/img/praznaKorpa.png" alt="Nema proizvoda" class="col-lg-5 col-12 col-sm-8">
                </div>
                <h2 class='text-center col-12 p-50-0 text-secondary' >Vaša korpa je trenutno prazna. Želite nazad na kupovinu?</h2>
                <a href="index.php?page=proizvodi" class="text-center text-secondary">
                    <h4 class="my-5">
                        <i class="fas fa-long-arrow-alt-left mr-2"></i>
                        Nazad na kupovinu
                    </h4>
                </a>`;

                $("#obrisiSve, #medjuzbir-placanje").addClass("d-none");
            }
            $("#korpaSpisak").html(html);

        });
    }

    function autofillTextboxCart() {
        ajaxFunction("models/user/get_user.php", 'GET', function (data) {
            let user = data;
            $("#korpa-imePrezime").val(user.ime + " " + user.prezime);
            $("#korpa-email").val(user.email);
        });
    }

    function totalPrice(medjuzbir) {
        let dostava = 250;
        if (medjuzbir >= 1500) {
            dostava = 0;
        }

        let ukupno = dostava + medjuzbir;
        $("#hidden-medjuzbir").val(ukupno);

        ukupno += ",00 RSD";
        dostava += ",00 RSD";
        medjuzbir += ",00 RSD";

        $("#tdCenaDostave").html(dostava);
        $("#tdMedjuzbir").html(medjuzbir);
        $("#tdUkupano").html(ukupno);
    }
    $(document).on("change", ".kolicina", function () {
        quantityChange($(this).data('id'), $(this).val());
    });
    $(document).on("click", ".obrisiIzKorpe", function () {
        deleteFromCart($(this).data('id'));
    });
    $(document).on("click", "#buttonObrisiKorpu", function () {
        deleteFromCart();
    });

    function quantityChange(id, val) {
        let dataAjax = {
            articleId: id,
            newValue: val
        }
        ajaxFunction("models/cart/change_quantity_of_item.php", 'POST', function (data) {
            loadCart();
            showCartQuantity();
        }, dataAjax);
    }

    function deleteFromCart(id = 0) {
        //console.log(articleId);
        ajaxFunction("models/user/get_user.php", 'GET', function (data) {
            var user = data;
            let dataAjax = { articleId: id }
            if (id == 0) {
                dataAjax = { userId: user.id_korisnik }
            }
            ajaxFunction("models/cart/delete_from_cart.php", 'POST', function (data) {
                loadCart();
                showCartQuantity();
            }, dataAjax);
        });
    }

    // form-validation (cart)
    $("#korpa-forma").submit(function (e) {
        resetFormMessages();
        validationError = false;

        if ($("#korpa-imePrezime").val().length < 8) {
            formErrorMessage("Unesite ispravno ime i prezime.", $("#korpa-imePrezime"));
        }
        if ($("#korpa-email").val().length < 3) {
            formErrorMessage("Unesite ispravanu email adresu.", $("#korpa-email"));
        }
        if ($("#korpa-adresa").val().length < 8) {
            formErrorMessage("Unesite ispravnu adresu.", $("#korpa-adresa"));
        }

        let fullName = $("#korpa-imePrezime").val();
        let email = $("#korpa-email").val();
        let address = $("#korpa-adresa").val();

        regexValidate(fullName, regexFullName, $("#korpa-imePrezime"));
        regexValidate(email, regexEmail, $("#korpa-email"));
        regexValidate(address, regexAddress, $("#korpa-adresa"));

        if (validationError) {
            e.preventDefault();
        }

    });

    // PRODUCT page proizvod-ukloni-button
    $("#proizvod-ukloni-button", ".proizvod-ukloni-button").click(function (e) {
        console.log(2);
        e.preventDefault();
        let id = $(this).data('id');
        let dataAjax = { id: id };
        ajaxFunction("models/admin/disable_product.php", 'POST', function (data) {
            if (data) {
                console.log(data);
                $("#proizvod-change-info")
                    .html("Proizvod je uspesno uklonjen.")
                    .removeClass("alert-warning")
                    .addClass("alert-success")
            }
            else {
                $("#proizvod-change-info")
                    .html("Doslo je do greske na serveru.")
                    .addClass("alert-warning")
                    .removeClass("alert-success")
            }
        }, dataAjax);

    });


    //lOGIN page
    var validationError = false;

    $("#prijava-forma").submit(function (event) {
        resetFormMessages();
        validationError = false;

        if ($("#email").val().length < 3) {
            formErrorMessage("Unesite ispravanu email adresu.", $("#email"));
        }
        if ($("#password").val().length < 8) {
            formErrorMessage("Unesite ispravnu lozinku.", $("#password"));
        }
        if (validationError) {
            event.preventDefault();
        }
    });



    // form messages
    function resetFormMessages() {
        validationError = false;
        $(".form-error").remove();
        $(".form-success").remove();
    }
    function formErrorMessage(message, element) {
        validationError = true;
        $(`<p class="form-error small text-danger">${message}</p>`).insertAfter($(element));
    }

    // regular expresion 
    let regexName = /^[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}(\s[A-ZŠĐŽĆČ][a-zšđžćč]{2,15})?$/;
    let regexEmail = /^[a-z]((\.|-|_)?[a-z0-9]){2,}@[a-z]((\.|-|_)?[a-z0-9]+){2,}\.[a-z]{2,6}$/i;
    let regexPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
    let regexFullName = /^[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}(\s[A-ZŠĐŽĆČ][a-zšđžćč]{2,15}){0,2}$/;
    let regexAddress = /^[\w\.]+(,?\s[\w\.]+){2,8}$/;

    // register
    $("#registracija-forma").submit(function (event) {
        resetFormMessages();
        validationError = false;


        let firstName = $("#firstName").val();
        let lastName = $("#lastName").val();
        let email = $("#email").val();
        let password = $("#password").val();
        let confPassword = $("#confirm-password").val();

        regexValidate(firstName, regexName, $("#firstName"));
        regexValidate(lastName, regexName, $("#lastName"));
        regexValidate(email, regexEmail, $("#email"));
        regexValidate(password, regexPassword, $("#password"));

        if (password !== confPassword) {
            formErrorMessage("Neispravno uneta potvrda lozinke.", $("#confirm-password"))
        }

        if (validationError) {
            //console.log('greska ima');
            event.preventDefault();
        }

    });

    // validation functions
    function regexValidate(string, regex, htmlElement) {
        if (string == "") {
            return formErrorMessage("Polje ne moze biti prazno.", htmlElement);
        }
        else if (!regex.test(string)) {
            return formErrorMessage("Neispravno unet podatak.", htmlElement);
        }
    }
    function textboxValidate(string, htmlElement) {
        if (string == "") {
            return formErrorMessage("Polje Teksta poruke je obavezno.", htmlElement);
        }
        if (string.length < 15) {
            return formErrorMessage("Tekst je prekratak za poruku.", htmlElement);
        }
    }

    // KONTAKT page validation
    $("#kontakt-form").submit(function (event) {
        resetFormMessages();
        validationError = false;


        let fullName = $("#name").val();
        let email = $("#email").val();
        let text = $("#text").val();

        regexValidate(fullName, regexFullName, $("#name"));
        regexValidate(email, regexEmail, $("#email"));
        textboxValidate(text, $("#text"));

        if (validationError) {
            console.log('greska ima');
            event.preventDefault();
        }

    });


    // ajax function
    function ajaxFunction(url, method, fun, data = {}) {
        $.ajax({
            url: url,
            method: method,
            dataType: "json",
            data: data,
            success: fun,
            error: function (xhr) {
                console.log(xhr);
            }
        });
    }

    // PROIZVODI page

    function loadProducts() {
        try {
            let ajaxResponse = ajaxFunction('models/products/get_products.php', 'get', function (data) {
                showProducts(data.products);
                printPagination(data.pageCount);
            });

        } catch (error) {

        }
    }

    $("#sort, #kategorije, #cena, #search").change(filterChange);

    function showProducts(products) {
        let html = "";
        if (products.length == 0) {
            $("#div-proizvodi").html("<p class='col-12 text-center alert-danger'>Nema proizvoda za odabrani filter.</p>");
            $("#paginacija").html("");
            return;
        }
        for (let p of products) {
            html += `
            <a href="index.php?page=proizvod&p_id=${p.id_pakovanje}" class="border-none">
                <div class="card col-5 col-lg-3 p-0 mb-5 mx-4">
                    <img class="card-img-top" src="assets/img/${p.slika_umanjena}" alt="${p.naziv}">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-1 text-dark">${p.naziv}</h5>
                        <p class="text-muted">${p.kolicina} ${stringMernaJedinica(p.merna_jedinica)} </p>
                        <div class="dot col-12 text-center">●</div>
                        <p class="card-text font-weight-bold text-dark">${p.cena},00 rsd</p>
                        <div class="dot col-12 text-center">●</div>
                        <a href="index.php?page=proizvod&p_id=${p.id_pakovanje}" class="border-none text-success col-12 detaljnije-link"><u>Detaljnije</u></a>
                        <a href="" class="btn btn-success border-none mt-3 dodajUKorpu" data-id="${p.id_pakovanje}">Dodaj u korpu</a>
                    </div>
                </div>
            </a>`;
        }
        $("#div-proizvodi").html(html);
        checkAdmin();
    }


    function printPagination(page) {

        pages = Math.ceil(page / 6);
        let html = "";
        for (let i = 0; i < pages; i++) {
            html += `
                <li class="page-item">
                    <a class="page-link dot pagination-link ${i == 0 ? "activePagination" : ""}" href="#" data-limit="${i}">${i + 1}</a>
                </li>`;
        }
        $("#pagination").html(html);
    }

    function filterChange(page = false) {

        let dataForAjax = {
            "sort": $("#sort").val(),
            "category": $("#kategorije input:checked").get().map(x => x.value).join(", "),
            "price": $("#cena input:checked").get().map(x => x.value).join(", "),
            "search": $("#search").val()
        };

        if (page !== false) {
            dataForAjax.limit = String(page);
        }

        let ajaxResponse = ajaxFunction("models/products/get_products.php", "get", function (data) {
            showProducts(data.products);
            console.log(data);


            if (page === false) {

                printPagination(data.pageCount);

            }

        }, dataForAjax);
    }

    function stringMernaJedinica(dbString) {
        if (dbString == "gram") {
            return "g";
        }
        else if (dbString == "kilogram") {
            return "kg";
        }
        else if (dbString == "mililitar") {
            return "ml";
        }
        return dbString;
    }

    $(document).on('click', '.pagination-link', function (e) {
        // e.preventDefault();

        let limit = $(this).data('limit');

        filterChange(limit);

        $('.pagination-link').removeClass("activePagination");
        $(this).addClass("activePagination");

    });

}