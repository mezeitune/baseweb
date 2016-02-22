<?php

chdir("..");

include_once 'util/includes.php';
include_once 'util/util.php';

$sinNovedad = true;
$intervalo = 500000;
$enviar = "VACIO";

$i = 0;

$objetivo = $_REQUEST["objetivo"];

$cambios = array();

while ($sinNovedad && $i < 18) {
    usleep($intervalo);

    switch ($objetivo) {
        default:
        case "ALERTAS":

            $lastUpdate = $_REQUEST["lastUpdate"];

            $alertas = Alerta::nuevasAlertas($lastUpdate, $BD);

            if ($alertas) {
                $cambios["alertas"] = $alertas;
                $sinNovedad = false;
                $enviar = json_encode($cambios);
            }
            break;
    }
    $i++;
}
echo $enviar;
?>
