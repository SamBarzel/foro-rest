<?php
require_once 'connection.php';
require_once 'jwt.php';

//validacion jwt
$jwt = apache_request_headers()['Authorization']; //Para obtener los encabezados
if(strstr($jwt, "Bearer") ){
    $jwt = substr($jwt, 7);
}
if(JWT::verify($jwt, Config::SECRET) ){ // si es 0 es valido, si es 1 es invalido por que esta modificado y si es 2 expiro
    header("HTTP/1.1 401 Unauthorized");
    exit();
}
$metodo = $_SERVER['REQUEST_METHOD']; //almacena el metodo que pide

switch ($metodo) {
    case 'GET':
        //consulta
        $_con = connection();
        if (isset($_GET['id'])) {
            $sql = $_con->prepare("SELECT * FROM questions WHERE id=:id");
            $sql->bindValue(":id", $_GET['id']);
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            $result = $sql->fetch();
        } else {
            $_con = connection();
            $sql = $_con->prepare("SELECT * FROM questions");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            $result = $sql->fetchAll();
        }
        echo json_encode($result);
        break;
    case 'POST':
        //agregar y insertar
        if (
            isset($_POST['id']) &&
            isset($_POST['question']) &&
            isset($_POST['email']) &&
            isset($_POST['date']) &&
            isset($_POST['topic_id'])
        ) {
            $_con = connection();
            $sql = $_con->prepare("INSERT INTO questions VALUES(:i, :q, :e, :d, :t)");
            $sql->bindValue(":i", $_POST['id']);
            $sql->bindValue(":q", $_POST['question']);
            $sql->bindValue(":e", $_POST['email']);
            $sql->bindValue(":d", $_POST['date']);
            $sql->bindValue(":t", $_POST['topic_id']);
            $sql->execute();
            echo json_encode(["status" => "agregado"]);
        } else {
            header("HTTP/1.1 400 Bad Request");
        }
        break;
    case 'PUT':
        //actualizar
        if (
            isset($_GET['id']) &&
            isset($_GET['question']) &&
            isset($_GET['email']) &&
            isset($_GET['date']) &&
            isset($_GET['topic_id'])
        ) {
            $_con = connection();
            $sql = $_con->prepare("UPDATE questions SET question=:q, email=:e, date=:d, topic_id=:t WHERE id=:i");
            $sql->bindValue(":i", $_GET['id']);
            $sql->bindValue(":q", $_GET['question']);
            $sql->bindValue(":e", $_GET['email']);
            $sql->bindValue(":d", $_GET['date']);
            $sql->bindValue(":t", $_GET['topic_id']);
            $sql->execute();
            echo json_encode(["status" => "actualizado"]);
        } else {
            header("HTTP/1.1 40 Bad Request");
        }
        break;
    case 'DELETE':
        //eliminar
        if (
            isset($_GET['id'])) {
            $_con = connection();
            $sql = $_con->prepare("DELETE FROM questions WHERE id=:i");
            $sql->bindValue(":i", $_GET['id']);
            $sql->execute();
            echo json_encode(["status" => "eliminado"]);
        } else {
            header("HTTP/1.1 40 Bad Request");
        }
        break;
    default:
        //error
        header("HTTP/1.1 405 Method No Allowed");
}