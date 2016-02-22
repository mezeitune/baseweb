$(document).ready(function() {
    $("[tooltip]").tooltip();
    $.ajaxSetup({
        statusCode: {405: function() {
                redirectHome();
            }}
    });
});

function mostrarLoading(){
    $("#divLoading").fadeIn();
}

function ocultarLoading(){
    $("#divLoading").fadeOut();
}

function enter(e, func, params){
    var keycode;
    if (window.event) 
        keycode = window.event.keyCode;
    else 
        if (e) 
            keycode = e.which;
    if(keycode === 13)
        func(params);
    
}

function redirect(url){
    location.href = url;
}

function verificarModificacion(input, idBtn){
    
    input = $(input);
    if(input.val() !== input.attr("value")){        
        $("#"+idBtn).attr("class", "btn btn-success");
    }else{
        $("#"+idBtn).attr("class", "btn");
    }
    $("#"+idBtn).click(function(){
        input.attr("value", input.val());
    });
}

function borrarCookie(name){
    
    var date = new Date();
    date.setDate(date.getDate() -1);
    document.cookie = escape(name) + '=;expires=' + date;
}

function alerta(msj){
    alert(msj);
}

function logout(){
    mostrarLoading();      
    
    borrarCookie("UID");
    borrarCookie("UHASH");
    $.ajax({
        url: "acciones/Logout.php",
        type: "POST",
        timeout: 10000,
        success: function(data){                
            location.href = data;      
            ocultarLoading();
        },
        error: function(){
            location.reload(true);            
            ocultarLoading();
        }
    });
}

function enModal(url, params, callback, hideCallback) {

    $('#divModal').html($("#divModalLoading").html());
    $("#divModal").modal("show");

    $('#divModal').on('hidden.bs.modal', function(e) {
        if (hideCallback)
            hideCallback();
    });

    $.ajax({
        url: url,
        data: params,
        type: 'POST',
        success: function(data) {
            $('#divModal').html(data);
            $("[tooltip]").tooltip();
            if (callback)
                callback();
            $("[tooltip]").tooltip();
        }
    });
}

function cerrarModal() {
    $("#divModal").modal("hide");
}

function enDiv(url, div, params, callback, divloading) {

    if (divloading)
        $("#" + div).addClass("div-load")
                .append($("#divLoader").html());

    $.ajax({
        url: url,
        data: params,
        success: function(data) {
            $('#' + div).html(data);
            if (callback)
                callback();
            if (divloading)
                $("#" + div).removeClass("div-load");
            $("[tooltip]").tooltip();
        }
    });
}

function alerta(msj, callback, titulo) {
    bootbox.alert({
        message: msj,
        title: titulo ? titulo : "Atenci&oacute;n",
        callback: callback
    });
}
function confirmar(msj, success, error) {
    bootbox.confirm({
        message: msj,
        title: "Atenci&oacute;n",
        callback: function(result) {
            if (result !== null && result) {
                success();
            } else {
                if (error)
                    error();
            }
        }
    });
}


function accion(accion, data, callback, errorCallback) {
    $.ajax({
        url: "acciones/" + accion,
        data: data,
        type: "POST",
        dataType: "json",
        timeout: 50000,
        success: function(data) {
            if (data.error) {
                alerta(data.error);
                if (errorCallback)
                    errorCallback(data);
            } else {
                if (callback)
                    callback(data);
            }
        },
        error: function() {
            alerta("Hubo un inconveniente, y la operaci&oacute;n no pudo llevarse a cabo. " 
                    + "Por favor intente nuevamente. Muchas gracias");
        }
    });
}



function validar(form) {
    var ret = true;
    $(".has-error").removeClass("has-error");
    form.find(".obligatorio").each(
            function(ns, fGroup) {
                $(fGroup).find("input").each(
                        function(ns, input) {
                            if ($(input).val() === "") {
                                $(fGroup).addClass("has-error");
                                if (ret)
                                    $(input).focus();
                                ret = false;
                            }
                        });
            });

    return ret;
}
