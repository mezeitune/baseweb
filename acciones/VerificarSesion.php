<?php

$nivel = $_REQUEST["nivel"];
if ($nivel && $nivel != "") {

    $requiereLogueo = true;
    $ajax = true;
    chdir("..");
    include_once 'util/includes.php';
    include_once 'util/util.php';
}
?>