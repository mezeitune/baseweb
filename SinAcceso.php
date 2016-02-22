<?php
include_once 'util/includes.php';
include_once 'util/util.php';
/**
 * @author Agustin Arias <aarias@adoxweb.com.ar>
 */

$urlVolver = $_REQUEST["back"];
$nivel = $_REQUEST["nivel"];

$titulo = "Intento fallido de acceso";
include 'vistas/SinAcceso.php';
?>
