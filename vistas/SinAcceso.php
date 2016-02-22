<?php include 'top.php'; ?>

<div class="row">
    <div class="col-md-11 col-md-offset-1">
        <h1 class="text-error">Intento de acceso sin permisos suficientes</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">        
        <p>
            Ha intentado acceder a <b><? echo $urlVolver; ?></b> sin los permisos
            suficientes. Para poder acceder a estos datos debe estar logueado con 
            un usuario que tenga permisos de <b><? echo Usuario::getNivelStr($nivel);?></b>
        </p>
        <p>
            Proceda a tomar cualquiera de las siguientes acciones:
        <div class="text-center">
            <button class="btn btn-large" onclick="history.back();">
                Ir atras
            </button>
            <button class="btn btn-large btn-primary" onclick="location.href = '<? echo $system["URL_LOGIN"] . "?back=" . $urlVolver; ?>'">
                Login
            </button>
        </div>
        </p>
    </div>
</div>

<?php include 'bottom.php'; ?>