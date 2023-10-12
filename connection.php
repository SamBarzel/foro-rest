<?php
function connection(){
try{
        $c = new PDO("mysql:host=localhost;dbname=foro", "root", "");
        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $c;
}catch(PDOException $e){
    die("Error: " .$e->getMessage());
    }

}