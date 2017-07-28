<?php

    $config= array();

    if($_SERVER["SERVER_NAME"] == "localhost"){

        $db = new Database('localhost', 'root','','zadatak');
    }else{
        $db = new Database('localhost', 'root','','dbname');
    }

foreach($config as $key => $value)
{
    defined($key) ? "" : define($key, $value);
}