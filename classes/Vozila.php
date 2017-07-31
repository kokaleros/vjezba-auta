<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28-Jul-17
 * Time: 14:44
 */
Class Vozila extends Database{

    public $insertedID = false;

    function __construct()
    {
        $this->open_connection("localhost", "root","","zadatak");
    }

    public function markeVozila(){
        $markeVozila = $this->get_result_array("SELECT * FROM marka_vozila ORDER BY marka ASC");
        return $markeVozila;
    }

    public function modeliVozila($id){
        if(!is_numeric($id)){
            die("ID mora biti numericki");
        }

        return $this->get_result_array("SELECT * FROM model_vozila WHERE marka_id = '" . $id ."' ORDER BY model ASC");
    }

    public function opremaVozila(){
        return $this->get_result_array("SELECT * FROM oprema ORDER BY naziv_opreme ASC");
    }

    public function snimiVozilo($_idModel, $_godiste, $_boja, $_sifra){
        $this->insertedID = $this->insert_single("INSERT INTO vozila (model_id, boja, godiste, sifra) VALUE ('". $_idModel ."', '". $_boja ."', '". $_godiste ."', '". $_sifra ."')");
        return $this->insertedID;
    }

    public function snimiOpremu($_voziloId, $_opremaArray){

        foreach ($_opremaArray as $oprema){
            $this->insert_single("INSERT INTO vozilo_oprema (vozilo_id, oprema_id) VALUE ('". $_voziloId ."', '". $oprema ."')");
        }
    }

    public function svaVozila(){
        //nadji sva vozila
        $svaVozila = $this->get_result("SELECT vozila.id as id, marka_vozila.marka, model_vozila.model as model, godiste, boja, sifra FROM zadatak.vozila, model_vozila, marka_vozila WHERE model_vozila.id = vozila.model_id AND model_vozila.marka_id = marka_vozila.id ORDER BY vozila.id DESC");

        //nadji opremu za svako vozilo iz liste
        for($i=0;$i<count($svaVozila); $i++){
            $voziloID = $svaVozila[$i]->id;
            $trenutnaOprema = $this->get_result_array("SELECT naziv_opreme FROM oprema, vozilo_oprema WHERE oprema.id = vozilo_oprema.oprema_id AND vozilo_id = '". $voziloID ."'");
            $svaVozila[$i]->oprema = $this->formatirajOpremu($trenutnaOprema);
        }

        echo json_encode($svaVozila);
    }

    private function formatirajOpremu($_oprema){
        if( !is_array($_oprema) or empty($_oprema)){
            return "";
        }else{
            $string = "";
            foreach ($_oprema as $stavka){
                $string .= $stavka['naziv_opreme'] . ", ";
            }
            //izbrisi zadnji zarez i space
            $string = substr($string, 0, strlen($string) -2);
            return $string;
        }
    }

    public static function generisiSifruVozila($_idMarka, $_idModel, $_godiste, $_boja){

        //dodaj vodecu nulu isprem marke ako je potrebno
        $sifra  = $_idMarka < 10 ? "0" . $_idMarka : $_idMarka;

        //dodaj vodece nule ispred modela automobila
        if($_idModel < 10){
            $sifra .= "00" . $_idModel;
        }elseif ($_idModel >= 10 && $_idModel < 100){
            $sifra .= "0" . $_idModel;
        }

        //zadnje dvije cifre godista i boja
        $sifra  .= substr($_godiste, 2) . $_boja;

        return $sifra;
    }

}