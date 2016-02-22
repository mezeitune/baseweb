/* LOGIN */

function login() {

    var btn = $("#btnLogin");
    var incompletos = "";
    $(".has-error").removeClass("has-error");

    if ($("#parPass").val() === "") {
        $("#fgPass").addClass("has-error");
        $("#parPass").focus();
    }
    if ($("#parUsername").val() === "") {
        $("#fgUsername").addClass("has-error");
        $("#parUsername").focus();
    }

    if ($(".has-error").length !== 0) {
        return;
    }

    btn.button("loading");
    accion("Login.php", $("#formLogin").serialize(),
            function(data) {
                if (data && data.e !== "OK") {
                    btn.button("reset");
                    alerta(data.msj);
                } else {
                    btn.attr("value", "<b>Login exitoso!</b> Redireccionando..");
                    location.href = $("#formLogin").attr("goto");
                }
            },
            function() {
                btn.button("reset");
                alerta("Por favor, intente nuevamente. "
                        + "Si el problema persiste comuniquese con nostros para informar el error.",
                        false, "Lo sentimos, ha ocurrido un error.");
            }
    );

}
