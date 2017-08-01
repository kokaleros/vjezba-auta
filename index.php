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
    <link href="http://fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

    <!-- CSS
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/skeleton.css">

    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="js/main.js"> </script>

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

    <div class="row" style="margin-top: 30px;">

        <table id="lista" border="1" width="100%">
            <thead>
                <tr>
                    <td width="130px">Marka</td>
                    <td>Model</td>
                    <td>Boja</td>
                    <td>Godiste</td>
                    <td width="100px">Sifra</td>
                    <td width="350px">Oprema</td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>
</div>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>
