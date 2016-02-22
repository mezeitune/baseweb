<?php
/**
 * DESCRIPCION
 * 
 * @author Agustin Arias <aarias@adoxweb.com.ar>
 */

chdir("..");
include_once 'util/includes.php';
include_once 'util/util.php';

$usuario = new Usuario();

$username = $_POST["username"] ?  $_POST["username"] : 'aarias';
$pass = $_POST["pass"] ?  $_POST["pass"] : '';

if ($usuario->login($username, $pass, $BD)){    
    logInfo("Login. Usuario: $username");
    $ret["e"] = "OK";
}else{
    logInfo("Fallo login. Usuario: $username");
    $ret["e"] = "ERROR";
    $ret["error"] = "<B>Lo sentimos.</B> La combinaci&oacute;n de usuario y contrase&ntilde;a no es correcta.";
}
echo json_encode($ret);
?>
