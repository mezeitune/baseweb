<? 

date_default_timezone_set('America/Argentina/Buenos_Aires');
error_reporting(E_ALL  ^ E_NOTICE ^ E_STRICT);
ini_set('display_errors', 1);

foreach (glob("model/*.php") as $filename) {
    include_once $filename;
} ?>