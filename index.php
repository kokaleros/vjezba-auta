<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28-Jul-17
 * Time: 14:39
 */

//require 'classes/Database.php';
//require 'classes/Vozila.php';

//$vozila         = new Vozila();
//$markeVozila    = $vozila->markeVozila();
//$modeliVozila   = $vozila->modeliVozila(2);

//var_dump($markeVozila);
//var_dump($modeliVozila);
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Basic Page Needs
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta charset="utf-8">
    <title>Your page title here :)</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Mobile Specific Metas
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FONT
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

    <!-- CSS
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/skeleton.css">

    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <!-- Favicon
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" type="image/png" href="images/favicon.png">

</head>
<body>

<!-- Primary Page Layout
–––––––––––––––––––––––––––––––––––––––––––––––––– -->
<div class="container">

<!--    marka vozila -->
    <div class="row">
        <div class="u-full-width" style="margin-top: 25%">
            <select name="markeVozila" disabled>
                <option>Izaberite marku vozila</option>
            </select>
        </div>
    </div>

<!--    Modeli vozila -->
    <div class="row">
        <div class="u-full-width">
                <select name="modeliVozila" style="visibility: hidden;">
                    <option>Izaberite model vozila</option>
                </select>
        </div>
    </div>


    <div class="row unosPodataka" style="visibility: hidden;">
        <div class="u-full-width">
            <div class="row">
                <label for="godiste">Godiste:</label>
                <input type="number" id="godiste" name="godiste" value="" placeholder="2007" min="1920" max="2017">

                <label for="boja">Boja:</label>
                <input type="text" id="boja" name="boja" value="" placeholder="crna" minlength="3" maxlength="40">
            </div>

            <div class="row oprema"></div>
        </div>
    </div>

</div>


<script type="text/javascript">

    $(document).ready(function () {
        init();

        function init(){
            //nadji sva vozila i dodaj ih u select box

            $.post( "api.php?option=sveMarkeVozila", function(data,status) {
                if(status != "success"){
                    console.log("Greska: ajax - ");
                    return false;
                }

                //svi podaci
                var markeVozila = JSON.parse(data);

                for(var i=0; i < markeVozila.length;i++){
                    $('select[name=markeVozila]').append($('<option>', {
                        value: markeVozila[i]["id"],
                        text: markeVozila[i]["marka"]
                    }));
                }

                $('select[name=markeVozila]').prop('disabled', false);

            });

        }

        $("select[name=markeVozila]").change(function () {
            var idMarkeVozila = $(this).val();
            if (idMarkeVozila == "Izaberite marku vozila") {
                return false;
            }

            //stavi da bude disable dok ne pronadje modele!
            $('select[name=modeliVozila]').prop("disabled", true);

            console.log(idMarkeVozila);

            //nadji modele tog vozila
            $.post("api.php?option=sviModeliVozila&id=" + idMarkeVozila, function (data, status) {
                if (status != "success") {
                    console.log("Greska: ajax - ");
                    return false;
                } else if (data == "false") {
                    alert("Nema podataka za tu marku!")
                    return false;
                }

                //izbrisi sve predhodne elemente iz select-a
                $('select[name=modeliVozila] option').not(':first').remove();


                //svi podaci
                var modeliVozila = JSON.parse(data);

                for (var i = 0; i < modeliVozila.length; i++) {

                    $('select[name=modeliVozila]').append($('<option>', {
                        value: modeliVozila[i]["id"],
                        text: modeliVozila[i]["model"]
                    }));
                }

                $('select[name=modeliVozila]').prop("disabled", false);
                $('select[name=modeliVozila]').css("visibility", "visible");

            });


        })

        $("select[name=modeliVozila]").change(function () {
            var idModelaVozila = $(this).val();
            if (idModelaVozila == "Izaberite model vozila") {
                $('.unosPodataka').css('visibility','hidden');
                return false;
            }

            izbrisiOpremu();
            $('.unosPodataka').css('visibility','visible');

            //nadji svu opremu vozila
            $.post("api.php?option=opremaVozila", function (data, status) {
                if (status != "success") {
                    console.log("Greska: ajax - oprema");
                    return false;
                }

                //svi podaci
                var oprema = JSON.parse(data);

                for(var i=0; i < oprema.length;i++){

                    $('.oprema').append($('<input type="checkbox"> ' +oprema[i]['naziv_opreme']+ '<br>', {
                        type:   'checkbox',
                        name:   'oprema',
                        value:  oprema[i]["id"]
                    }));
                }
            });
        });

        function izbrisiOpremu() {
            $('.oprema').html("");
        }

    });

</script>
<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>
