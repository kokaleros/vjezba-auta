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

    <div class="row" style="margin-top: 15%">

        <div class="row one-half column">
            <!--    marka vozila -->
            <div class="row">
                <div id="markeVozila" class="u-full-width loading">
                    <select name="markeVozila" disabled>
                        <option>Izaberite marku vozila</option>
                    </select>
                </div>
            </div>

            <!--    Modeli vozila -->
            <div class="row">
                <div id="modeliVozila" class="u-full-width">
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
                        <input type="text" id="boja" name="boja" value="" placeholder="ff000" minlength="6" maxlength="6">
                    </div>
                </div>
            </div>
        </div>

        <div class="row one-half column">
            <div class="row unosPodataka oprema"></div>
        </div>

        <div class="u-full-width">
            <div id="snimanje" class="row u-full-width" style="visibility: hidden;">
                <input type="submit" name="snimi" value="Save">
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 30px;"></div>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        var idMarka;
        var idModel;
        var godiste;
        var boja;
        var oprema = [];

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

                $('#markeVozila').removeClass('loading');
                $('select[name=markeVozila]').prop('disabled', false);

            });

        }

        $("select[name=markeVozila]").change(function () {
            var idMarkeVozila = $(this).val();
            if (idMarkeVozila == "Izaberite marku vozila") {
                return false;
            }

            idMarka = idMarkeVozila;

            //stavi da bude disable dok ne pronadje modele!
            $('#modeliVozila').addClass('loading');
            $('select[name=modeliVozila]').prop("disabled", true);
            $('select[name=modeliVozila]').css("visibility", "visible");

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
                $('#modeliVozila').removeClass('loading');

            });


        })

        $("select[name=modeliVozila]").change(function () {
            var idModelaVozila = $(this).val();
            if (idModelaVozila == "Izaberite model vozila") {
                $('.unosPodataka').css('visibility','hidden');
                return false;
            }

            idModel = idModelaVozila;

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
                    var trenutnaOprema = $('<input>', {
                        type:   'checkbox',
                        name:   'oprema',
                        value:  oprema[i]["id"]
                    });

                    $(".oprema").append(trenutnaOprema);
                    $(".oprema").append(' '+ oprema[i]['naziv_opreme'] + '<br>');
                }
            });

            //prikazi snimi dugme
            $('input[name=snimi]').css('visibility','visible');
        });

        $('input[name=snimi]').click(function () {

            var unesenaOprema = [];

            $(".oprema input[type=checkbox]:checked").each(function () {
                unesenaOprema.push(this.value);
            });

            oprema = unesenaOprema;
            boja = $("#boja").val();
            godiste = $("#godiste").val();

            $.post( "api.php?option=snimiVozilo",
                {
                    thru_api: 1,
                    marka: idMarka,
                    model: idModel,
                    boja: boja,
                    godiste: godiste,
                    oprema: oprema
                })
                .done(function( data ) {
                    if(data == 'success'){
                        //reset form
                        resetujFormu();

                        //reload list of models

                    }else{
                        alert(data);
                    }
                });
        });




        function resetujFormu() {
            $("select[name=markeVozila]").val($("select[name=markeVozila] option:first").val());
            $('select[name=modeliVozila]').css("visibility", "hidden");
            $('select[name=modeliVozila] option').not(':first').remove();
            $('.unosPodataka').css('visibility','hidden');
            $('#boja').val('');
            $('#godiste').val('');

            izbrisiOpremu(true);
            $('input[name=snimi]').css('visibility','hidden');
        }
        
        function izbrisiOpremu(clear = false) {
            if (clear == true) {
                $('.oprema').html();
            } else {
                $('.oprema').html("<p>Oprema: </p>");
            }
        }

    });

</script>
<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>
