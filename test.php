<?php
//include("connection.php");
//require("connection.php");
//include_once("connection.php");
require_once("connection.php");

$con = connection();
if($con){
    echo "Conexion exitosa";
}else{
    echo "Error en la conexion";
}
