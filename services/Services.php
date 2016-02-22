<?php
/**
 * Script encargado de iniciar y procesar servicios externos.
 *
 * @author Agustin Arias <aarias@adoxweb.com.ar>
 */
chdir('..');
include_once 'util/util.php';

// Acciones que realiza la aplicación central.
const CODIGO = "COD";

if (isset($_REQUEST["acc"]))
    procesarAccion($_REQUEST["acc"]);

function procesarAccion($accion) {
    
    global $BD;
    global $system;
    global $labels;

    switch ($accion) {

        case CODIGO:
            // hacer algo
            break;

        default:
            break;
    }
}

function iniciarAccion($accion, $ip, $params = null) {
    $url = "http://$ip/?acc=$accion";

    if ($params != null)
        $url .= '&' . $params;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $page = curl_exec($ch);
    curl_close($ch);

    return $page;
}

?>