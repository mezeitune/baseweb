<?
include_once 'util.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><? echo $labels["TITULO"] . ($titulo ? " - " . $titulo : "") ?></title>
        <link rel="shortcut icon" href="img/fav.ico">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">  

        <!-- CSS -->
        <link href="css/common.css" rel="stylesheet" media="screen">       
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">   
        <!--        <link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">   -->
        <link href="css/font-awesome.min.css" rel="stylesheet" media="screen">      

        <? if ($extrasCss) foreach ($extrasCss as $eCss) { ?>
                <link href="<? echo $eCss; ?>" rel="stylesheet" media="screen">
            <? } ?>

        <? if ($incluirUI) { ?>
            <link href="css/custom-theme/jquery-ui-1.9.2.custom.css" rel="stylesheet" media="screen">
        <? } ?>

        <!-- JS -->
        <script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
        <? if ($incluirUI) { ?>
            <script src="js/jquery-ui-1.9.2.min.js" type="text/javascript"></script>
        <? } ?>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/bootbox.min.js" type="text/javascript"></script>
        <script src="js/common.js" type="text/javascript"></script>
        <? if ($extrasJs) foreach ($extrasJs as $ejs) { ?>
                <script src="<? echo $ejs; ?>" type="text/javascript"></script>
            <? } ?>

        <script type="text/javascript">

<? if ($requiereLogueo && !$noVerificarCambioPass) { ?>
                $(document).ready(function() {
                    verificarLogin();
                });
<? } ?>

            function redirectHome() {
                redirect('<? echo $system["URL_SINACCESO"] ?>');
            }
            function verificarLogin() {
                $.ajax({
                    url: "acciones/VerificarSesion.php",
                    data: 'nivel=<? echo $nivel; ?>',
                    type: "POST",
                    statusCode: {405: function() {
                            redirectHome();
                        }
                    }
                });
            }
        </script>
    </head>
    <body>
<!--        <div class="navbar navbar-default navbar-static-top" role="navigation">

            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Logo</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">

                        <li <? echo $menu == "HOME" ? 'class="active"' : ''; ?>>
                            <a href="Home">Inicio</a>
                        </li>

                    </ul>
                    <div class="navbar-form navbar-right">
                        <? if ($usuario && $usuario->logueado()) { ?>

                            <div class="userbox">
                                <span><? echo $usuario->getUsername(); ?></span>
                                <div class="logout">
                                    <button class="btn btn-default" onclick="logout()">
                                        <i class="glyphicon glyphicon-log-out"></i> Salir 
                                    </button>
                                </div>                        
                            </div>
                        <? } else { ?>                            
                            <a type="button" href="<? echo $system["URL_LOGIN"] ?>" 
                               class="btn btn-default">Acceder</a>
                           <? } ?>
                    </div>
                </div>
            </div>
        </div>-->
        <?
        $menu = new Menu($BD);
        $menu->mostrarOpciones($usuario, $BD);
        ?>
    </div>

    <div class="container">
