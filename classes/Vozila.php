<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28-Jul-17
 * Time: 14:44
 */
Class Vozila extends Database{

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

}