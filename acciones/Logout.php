<?php
/**
 * DESCRIPCION
 * 
 * @author Agustin Arias <aarias@adoxweb.com.ar>
 */

chdir("..");
include_once 'util/includes.php';
include_once 'util/util.php';

$usuario->logout($BD);
echo $system["URL_BASE"];

?>
