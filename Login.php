<?php
include_once 'util/includes.php';
include_once 'util/util.php';
/**
 * Controlador modelo.
 * 
 * @author Agustin Arias <aarias@adoxweb.com.ar>
 */

$extrasJs = array("js/Login.js");
$extrasCss = array("css/login.css");

$titulo = "LogIn";
$menu = "Login";
include 'vistas/Login.php';
?>
