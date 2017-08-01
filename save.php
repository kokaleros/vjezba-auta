<?php
require 'classes/Database.php';
require 'classes/Vozila.php';

$apiData = $_REQUEST;

if( $apiData["option"] == 'snimiVozilo' ){
    $post = $_POST;

    //provjeri sve podatke
    isset($post["thru_api"]) && $post["thru_api"] == 1 ? null : exit("No data!");

    isset($post["marka"]) && is_numeric($post["marka"]) ? null : exit("Greska pri unosu marke automobila");
    isset($post["model"]) && is_numeric($post["model"]) ? null : exit("Greska pri unosu modela automobila");
    isset($post["boja"]) && preg_match("/[\w]{6}/m", $post["boja"]) && strlen($post["boja"]) == 6 ? null : exit("Boja automobila nije unesena pravilno. Mora biti u hex kodu, format ff0000");
    isset($post["godiste"]) && preg_match("/[\d]{4}/", $post["godiste"]) ? null : exit("Godiste automobila nije unesena pravilno.");
    $post["godiste"] > 2017 || $post["godiste"] < 1900 ? die("Neispravno godiste automobila") : null;

    //generisi sifru
    $sifra  = Vozila::generisiSifruVozila($post["marka"], $post["model"], $post["godiste"], $post["boja"]);

    //snimi automobil
    $vozilo = new Vozila();
    $vozilo->snimiVozilo($post['model'], $post['godiste'], $post['boja'], $sifra);
    $vozilo->insertedID == false ? die("Automobil nije unesen u bazu! #22") : "";

    //snimi opremu
    if (isset($post['oprema']) && is_array($post['oprema']) && !empty($post['oprema'])) {
        $snimiOpremu = new Vozila();
        $snimiOpremu->snimiOpremu($vozilo->insertedID, $post['oprema']);
    }

    die('success');
}


?>