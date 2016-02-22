<?php

/**
 * DESCRIPCION
 * 
 * @author Agustin Arias <aarias@adoxweb.com.ar>
 */

define("MSJ_ERR","E");
define("MSJ_INF","I");
define("MSJ_DBG","D");

function init() {

    global $system;
    global $params;
    global $BD;
    global $labels;
    global $dbParams;
    global $usuario;
    global $requiereLogueo;
    global $noVerificarCambioPass;
    global $nivel;
    global $urlVolver;

    // Leo las propiedades de los archivos de configuracion
    $system = obtProperties("system.properties");
    $labels = obtProperties("labels.properties");
    $dbParams = obtProperties("bd.properties");

    // Consigo la conexion con la base de datos
    $BD = new BDCon($dbParams);

    // Leo los parametros de configuracion de la base de datos
    $params = Parametro::obtTodos($BD);

    $usuario = new Usuario();

    // Cargo la sesion, si es que hay
    $usuario->cargarSesion($BD);

    if ($requiereLogueo && (!$usuario->logueado() || !$usuario->tieneAcceso($nivel))) {
        logInfo("Intento de acceso a '" . $system["URL_BASE"] . $urlVolver . "'." .
                "Redirrecionado a '" . $system["URL_SINACCESO"] . "'.");
        redirect($system["URL_SINACCESO"]);
        return false;
    }

    if (!$noVerificarCambioPass && $usuario->getCambiarPass() == 'S')
        redirect($system["URL_CAMBIAR"]);

    return true;
}

/**
 * Lectura de propiedades
 * 
 * El archivo a leer debe estar redactados según el formato:
 * campo = valor
 * No se consideran los espacios antes o despues de los valores o campos.
 * 
 * Se aceptan comentarios con el formato:
 * #Comentario
 * 
 * @param Nombre del archivo .properties
 * @return Un array del tipo $array["campo"] = valor;
 * 
 * @author aarias
 * 
 */
function obtProperties($fileName) {

    $d = '=';
    $file = fopen("properties/" . $fileName, "r") or
            logError("No se pudo abrir el archivo de propiedades $fileName.");
    while (!feof($file)) {
        // Obtengo linea
        $linea = fgets($file);
        // Si hay propiedad lo agrego
        if (!strstr($linea[0], '#') && strstr($linea, $d)) {
            // Divido parámetro de valor
            $prop = explode($d, $linea);

            // Elimino espacios en blanco
            $prop[0] = trim($prop[0]);
            $prop[1] = trim(substr(stristr($linea, '='), 1));

            // Agrego las propiedades en minusculas y mayusculas.
            $props[$prop[0]] = str_replace("\n", "", $prop[1]);
            $props[strtoupper($prop[0])] = str_replace("\n", "", $prop[1]);
            $props[strtolower($prop[0])] = str_replace("\n", "", $prop[1]);
        }
    }
    fclose($file);

    return $props;
}

function enviarMail($mensaje, $aQuien, $motivo) {

    logDebug("ENVIO EMAIL: " . $aQuien . "  " . $motivo . "  " . $mensaje . "  From: " . htmlchars(Parametro::obtV(Parametro::FROM)));

//    mail($aQuien, $motivo, $mensaje, "From: " . Parametro::obtV(Parametro::FROM));
}

function enviarMailMultiple($mensaje, $aQuien, $motivo) {

//    mail($aQuien, $motivo, $mensaje, " From: " . Parametro::obtV(Parametro::FROM));
}

function redirect($url) {
    global $system;
    global $urlVolver;
//    global $nivel;
    global $ajax;

    $fullUrl = $url . "?back=" . $system["URL_BASE"] . $urlVolver; // . ($nivel ? "&nivel=" . $nivel : "");

    if (!$ajax)
        header("Location: " . $fullUrl);
    else {
        header("HTTP/1.0 405 Method Not Allowed");
        echo "<script>redirect('" . $fullUrl . "')</script>";
        ob_end_flush();
    }
}

function encrypt($string, $key = null) {
    global $system;

    if ($key == null)
        $key = $system["ENCR_KEY"];

    $result = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $result.=$char;
    }

    return str_replace("=", "-", base64_encode(base64_encode(base64_encode($result))));
}

function decrypt($string, $key = null) {
    global $system;

    if ($key == null)
        $key = $system["ENCR_KEY"];

    $result = '';
    $string = str_replace("-", "=", $string);
    $string = base64_decode(base64_decode(base64_decode($string)));
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) - ord($keychar));
        $result.=$char;
    }
    return $result;
}

function strdecode($str, $decode = true) {
    global $ajax;

    return $ajax || !$decode ? $str : utf8_decode($str);
}

function logError($msj) {
    $msj = print_r($msj, true);
//    echo "\r\n\r\n$msj\r\n\r\n";
    logMsj("#ERROR# " . $msj, MSJ_ERR);
}

function logInfo($msj) {
    $msj = print_r($msj, true);
    logMsj("#INFO# " . $msj, MSJ_INF);
}

function logDebug($msj) {
    $msj = print_r($msj, true);
    logMsj("#DEBUG# " . $msj, MSJ_DBG);
}

function logMsj($msj, $tipo = 1) {

    global $system;
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    if ($tipo === MSJ_ERR && $system["ERR_FILE"]) {
        $fileName = str_replace("#F#", date("Y-m-d"), $system["ERR_FILE"]);
        $file = fopen($fileName, "a") or
                die("No se pudo abrir el log de errores " . $fileName . ".");
    }
    if ($tipo === MSJ_INF && $system["INF_FILE"]) {
        $fileName = str_replace("#F#", date("Y-m-d"), $system["INF_FILE"]);
        $file = fopen($fileName, "a") or
                die("No se pudo abrir el log de información " . $fileName . ".");
    }
    if ($tipo === MSJ_DBG && $system["DBG_FILE"]) {
        $fileName = str_replace("#F#", date("Y-m-d"), $system["DBG_FILE"]);
        $file = fopen($fileName, "a") or
                die("No se pudo abrir el log de debug " . $fileName . ".");
    }

    $msj = "[" . date('d-m-Y H:i:s') . "] " . $msj . "\r\n";

    if ($file != null) {
        fwrite($file, $msj);
        fclose($file);
    }
}

function leerArchivo($nombre, $esHTML = true, $color = 'black') {

    $file = fopen($nombre, "r") or
            logError("No se pudo abrir el archivo " . $nombre . ".");
    $str = "";

    while (!feof($file)) {
        $str .= fread($file, 8192);
    }

    if ($esHTML) {
        $str = str_replace("\r\n", "</p><p>", $str);
        $str = str_replace("[", "<span class='hora'>[", $str);
        $str = str_replace("]", "]</span> +", $str);
        $str = str_replace("+ #", "<span class='tipo'>&LT;", $str);
        $str = str_replace("#", "&GT;</span>", $str);
        $str.= "</p>";
        $str = "<style>
                    body{ font-family: 'Arial'; font-size: 13px;}
                    p { color: #256D96;font-family: 'Arial';margin: 2px; }
                    p:hover { background-color: #EEEEEE; }
                    .hora {color: black; font-weight: bold; margin: 2px; }
                    .tipo {color: $color; }
                </style><p>" . $str;
    }


    if ($file != null) {
        fclose($file);
    }


    return $str;
}

/**
 * En caso de haber una redireccion no necesito y no debo enviar la información
 * solicitada, ya que el solicitante no estaba logueado con los permisos necesarios
 */
function borrarContenido($buff) {

    return "";
}

function htmlchars($str, $decode = true) {

    if (!$decode)
        $str = utf8_encode($str);

    return htmlspecialchars(strdecode($str, $decode));
}

/**
 * Verifica si la hora pasada se encuentra en el array de horas.
 * 
 * @param int $nhora El numero de hora (1-24) a buscar en el array
 * @param array(date) $horas El array con los timestamp a comparar
 */
function horaOcupada($nhora, $tareas) {

    foreach ($tareas as $tarea) {
        $hora = $tarea["hora"];
        if ($hora->format("H") == $nhora)
            return $tarea;
    }

    return false;
}

date_default_timezone_set('America/Argentina/Buenos_Aires');
// Variables globales
$system = array();
$params = array();
$labels = array();
$bdParams = array();
$BD = new BDCon(null);
$usuario = new Usuario();
$menuObj = new OpcionMenu();
$_ejecValida = true;
$_ejecValida = init();

if (!$_ejecValida)
    ob_start("borrarContenido");
?>
