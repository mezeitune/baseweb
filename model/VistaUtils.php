<?php

class VistaUtils {

    function __construct() {
        
    }

    public static function selectorFecha($nombre, $id, $sel = false, $class = 'fecha') {
        ?>
        <div class="input-group"> 
            <input type="text" class="form-control <?= $class; ?>" 
                   id="<?= $id; ?>" name="<?= $nombre; ?>"
                   value="<?= $sel ? $sel : ""; ?>">
            <span class="input-group-addon" 
                  title="Seleccionar fecha" tooltip
                  onclick='$("#<?= $id; ?>").datepicker("show")'>
                <i class="icon-calendar"></i></span>
            <span class="input-group-addon" title="Vaciar fecha" tooltip
                  onclick='$("#<?= $id; ?>").val("")'>
                <i class="icon-trash"></i></span>
        </div>
        <?
    }

    public static function comboTipos($tipos, $id, $sel = false, $name = false) {
        ?>
        <select id="<?= $id; ?>" name="<?= $name ? $name : $id; ?>" type="text" class="form-control">
            <? foreach ($tipos as $tipo) { ?>
                <option value='<?= $tipo->getId() ?>'
                        <?= $sel == $tipo->getId() ? "selected" : "" ?>>
                            <?= $tipo->getDescripcion() ?>
                </option>
            <? } ?>
        </select>
        <?
    }

    public static function comboTiposDesc($tipos, $id, $sel = false, $name = false) {
        ?>
        <select id="<?= $id; ?>" name="<?= $name ? $name : $id; ?>" type="text" class="form-control">
            <? foreach ($tipos as $tipo) { ?>
                <option value='<?= $tipo->getDescripcion() ?>'
                        <?= $sel == $tipo->getDescripcion() ? "selected" : "" ?>>
                            <?= $tipo->getDescripcion() ?>
                </option>
            <? } ?>
        </select>
        <?
    }

}
?>
