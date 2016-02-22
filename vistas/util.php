<?php

/**
 * DESCRIPCION
 * 
 * @author Agustin Arias <aarias@adoxweb.com.ar>
 */

function formulario($form){
    
    $sliders = $form["sliders"];
    $inputs = $form["inputs"];
    $fechas = $form["fechas"];
    $combos = $form["combos"];
    $textAreas = $form["textAreas"];
    $btn = $form["btn"];
    ?>

    <form class="form-horizontal" id="form<? echo $form["id"] ?>">
        <? foreach ($inputs as $input) { ?>        
            <div class="control-group">
                <label class="control-label" for="<? echo $input["id"] ?>"><? echo $input["desc"] ?></label>
                <div class="controls">
                    <input type="text" id="<? echo $input["id"] ?>" name="<? echo $input["id"] ?>"
                           value="<? echo $input["val"] ?>" placeholder="<? echo $input["pholder"] ?>"
                           onkeyup="verificarModificacion(this, '<? echo $btn["id"] ?>');">
                </div>
            </div>
        <? } ?>
        <? foreach ($sliders as $slider) { ?>        
            <div class="control-group">
                <label class="control-label" for="<? echo $slider["id"] ?>">
                    <? echo $slider["desc"] ?>
                </label>
                <div class="controls">
                   <input type="text" class="slider" value="" 
                          data-slider-min="0" data-slider-max="100" 
                          data-slider-step="1" data-slider-value="50" 
                          data-slider-orientation="vertical" data-slider-selection="after"
                          data-slider-tooltip="hide">
                </div>
            </div>
        <? } ?>
        <? foreach ($fechas as $fecha) { ?>        
            <div class="control-group">
                <label class="control-label" for="<? echo $fecha["id"] ?>">
                    <? echo $fecha["desc"] ?>
                </label>
                <div class="controls">
                    <input type="text" id="<? echo $fecha["id"] ?>" name="<? echo $fecha["id"] ?>"
                           value="<? echo $fecha["val"] ?>" class="fecha"
                           onkeyup="verificarModificacion(this, '<? echo $btn["id"] ?>');">
                </div>
            </div>
        <? } ?>
        <? foreach ($combos as $combo) { ?>        
            <div class="control-group">
                <label class="control-label" for="<? echo $combo["id"] ?>">
                    <? echo $combo["desc"] ?>
                </label>
                <div class="controls">
                    <select type="text" id="<? echo $combo["id"] ?>" name="<? echo $combo["id"] ?>"
                           onchange="verificarModificacion(this, '<? echo $btn["id"] ?>');">
                        <option value=""><? echo $combo["blank"] ?></option>
                        <? foreach ($combo["valores"] as $valor) { ?>
                            <option value="<? echo $valor["cod"] ?>" 
                                    <? echo $combo["val"] == $valor["cod"] ? "selected":""?> >
                                <? echo $valor["desc"] ?></option>
                        <? } ?>
                    </select>
                </div>
            </div>
        <? } ?>
        <? foreach ($textAreas as $textArea) { ?>        
            <div class="control-group">
                <label class="control-label" for="<? echo $textArea["id"] ?>">
                    <? echo $textArea["desc"] ?>
                </label>
                <div class="controls">
                    <textarea id="parDescripcion" name="parDescripcion" placeholder="<? echo $textArea["pholder"] ?>"
                              onkeyup="verificarModificacion(this, '<? echo $btn["id"] ?>');"
                              rows="3"><? echo $textArea["val"] ?></textarea>
                </div>
            </div>
        <? } ?>
        <div class="mensajes">
            <div id="msjError<? echo $form["id"] ?>" class="alert alert-error text-left hide"></div>
            <div id="msjOk<? echo $form["id"] ?>" class="alert alert-success text-left hide"></div>
            <div id="msjInfo<? echo $form["id"] ?>" class="alert alert-info text-left hide"></div>
            <div id="msjLoad<? echo $form["id"] ?>" class="loading">
                <img src="img/load.gif"/>
            </div>   
        </div>
        <div class="control-group">
            <div class="controls">
                <button class="btn" id="<? echo $btn["id"] ?>" 
                        onclick="<? echo $btn["func"] ?>" type="button">
                    <? echo $btn["desc"] ?>
                </button>
            </div>
        </div>
    </form>
<?  
}
?>
