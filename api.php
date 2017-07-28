<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28-Jul-17
 * Time: 15:10
 */
require 'classes/Database.php';
require 'classes/Vozila.php';

$apiData = $_REQUEST;

isset($apiData["option"]) && !empty($apiData["option"]) ? null : die('There is no request!');


//ispisi sve marke vozila
if( $apiData["option"] == "sveMarkeVozila" ){
    $vozila         = new Vozila();
    $markeVozila    = $vozila->markeVozila();

    echo json_encode($markeVozila);
}


//ispisi sve modele vozila
if( $apiData["option"] == "sviModeliVozila" && isset($apiData["id"]) && is_numeric($apiData["id"]) ){
    $vozila         = new Vozila();
    $modeliVozila   = $vozila->modeliVozila($apiData["id"]);

    if($modeliVozila != false){
        echo json_encode($modeliVozila);
    }else{
        echo "false";
    }
}


//ispisi svu opremu
if( $apiData["option"] == "opremaVozila" ){
    $vozila         = new Vozila();
    $opremaVozila    = $vozila->opremaVozila();

    echo json_encode($opremaVozila);
}
