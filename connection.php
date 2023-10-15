<?php

require_once("config.php");
function connection(){
try{
        $c = new PDO("mysql:host=".Config::SERVER.";dbname=".Config::DB,
        Config::USER,Config::PASS);
        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $c->exec("use ".Config::DB);
        return $c;
}catch(PDOException $e){
    //die("Error: " .$e->getMessage());
    exit($e->getMessage());
    }
}