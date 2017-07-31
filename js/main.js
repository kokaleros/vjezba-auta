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

        izlistajSvaAuta();
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
                    //reload list of models
                    izlistajSvaAuta();

                    //reset form
                    resetujFormu();

                }else{
                    alert(data);
                }
            });
    });

    function izlistajSvaAuta() {

        $.post( "api.php?option=svaVozila", function( data ) {

            //svi podaci
            var vozila = JSON.parse(data);

            console.log(vozila);

            var elements = '';

            for(j=0; j < vozila.length; j++){
                var t = '<tr class="single">' +
                    '<td>'+ vozila[j]['marka'] +'</td>' +
                    '<td>'+ vozila[j]['model'] +'</td>' +
                    '<td>'+ vozila[j]['boja'] +'</td>' +
                    '<td>'+ vozila[j]['godiste'] +'</td>' +
                    '<td>'+ vozila[j]['sifra'] +'</td>' +
                    '<td>'+ vozila[j]['oprema'] +'</td></tr>';
                elements+=t;
            }

            $("#lista tbody").html("");
            $('#lista tbody').append(elements);
            });
    }

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

    function izbrisiOpremu(clear) {
        if (clear == true) {
            $('.oprema').html();
        } else {
            $('.oprema').html("<p>Oprema: </p>");
        }
    }

});