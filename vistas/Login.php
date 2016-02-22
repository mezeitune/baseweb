<?php
include 'top.php';

$back = isset($_REQUEST['back']) ? $_REQUEST['back'] : "";
if ($back == $system["URL_LOGIN"] || $back == "")
    $back = $system["URL_BASE"];
?>

<div class="row">
    <div class="col-md-4 col-md-offset-4 well">
        <form class="form-horizontal" id="formLogin" goto="<? echo $back; ?>">
            <div class="form-group" id="fgUsername">
                <label for="parUsername" class="col-lg-4 control-label">
                    Usuario
                </label>
                <div class="col-md-8">
                    <input type="text" class="form-control" 
                           name="username" id="parUsername"
                           placeholder="Mi usuario" onkeypress="enter(event,
                                           function() {
                                               $('#parPass').focus();
                                           });">
                    <span class="glyphicon glyphicon-remove form-control-feedback" 
                          aria-hidden="true"></span>
                </div>
            </div>
            <div class="form-group" id="fgPass">
                <label for="parPass" class="col-lg-4 control-label">
                    Contrase&ntilde;a
                </label>
                <div class="col-md-8">
                    <input type="password" class="form-control" 
                           name="pass" id="parPass"
                           placeholder="******" onkeypress="enter(event, login);">
                    <span class="glyphicon glyphicon-remove form-control-feedback" 
                          aria-hidden="true"></span>
                </div>
            </div>
            <div class="form-group text-center">
                <button class="btn btn-large btn-primary" 
                        data-loading-text="<i class='icon-spin icon-spinner'> </i> Entrar"
                        id="btnLogin" onclick="login();"
                        type="button">
                    <i class="icon-ok"></i>
                    Entrar</button>
            </div>
        </form>
    </div>
</div>	

<?php include 'bottom.php'; ?>
