<?php

class Menu {

    private $opciones;

    public function __construct(BDCon $BD) {
        $this->cargarOpciones($BD);
    }

    public function cargarOpciones(BDCon $BD) {
        $this->opciones = OpcionMenu::obtTodos($BD);
    }

    public function mostrarOpciones(Usuario $usuario, BDCon $BD) {
        global $menu;
        global $system;
        ?>
        <div class="navbar navbar-default navbar-static-top" role="navigation">

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

                        <?
                        logDebug($usuario);
                        foreach ($this->opciones as $opcion) {
                            if ($usuario && $usuario->tieneAcceso($opcion->getNivel())) {
                                ?>
                                <li <?= $menu == $opcion->getClave() ? 'class="active"' : ''; ?>>
                                    <? if (count($opcion->getHijos()) == 0) { ?>
                                        <a href="<?= $opcion->getUrl(); ?>"><?= $opcion->getTexto(); ?></a>
                                    <? } else { ?>
                                        <a href="<?= $opcion->getUrl(); ?>" data-toggle="dropdown">
                                            <?= $opcion->getTexto(); ?> <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <? foreach ($opcion->getHijos() as $hijo) { ?>
                                                <li>
                                                    <a href="<?= $hijo->getUrl(); ?>"><?= $hijo->getTexto(); ?></a>
                                                </li>
                                            <? } ?>
                                        </ul>
                                    <? } ?>
                                </li> 
                                <?
                            }
                        }
                        ?>

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
        </div>
        <?
    }

    public function getOpciones() {
        return $this->opciones;
    }

    public function setOpciones($opciones) {
        $this->opciones = $opciones;
    }

}

class OpcionMenu {

    private $id;
    private $idPadre;
    private $clave;
    private $texto;
    private $url;
    private $nivel;
    private $orden;
    private $hijos;

    public function __construct($rs = false) {
        if ($rs) {
            $this->id = $rs["id"];
            $this->idPadre = isset($rs["padre_id"]) ? $rs["padre_id"] : false;
            $this->clave = $rs["clave"];
            $this->texto = $rs["texto"];
            $this->url = $rs["url"];
            $this->nivel = $rs["nivel"];
            $this->orden = $rs["orden"];
        }
        $this->hijos = array();
    }

    public static function obtTodos($BD) {
        return self::organizar(self::obtMultiples("obtOpcionesMenu", false, $BD));
    }

    public static function obtMultiples($stmt, $params, BDCon $BD) {

        $params = $params ? $params : array();
        $rs = $BD->stmt($stmt, $params);
        $objs = array();

        while ($res = $BD->fetch($rs)) {
            $obj = new OpcionMenu($res);
            $objs[$obj->getId()] = $obj;
        }

        return $objs;
    }

    public static function organizar($opciones) {
        $opcion = new self();
        foreach ($opciones as $id => $opcion) {
            if ($opcion->idPadre) {
                $opciones[$opcion->idPadre]->hijos[] = $opcion;
                unset($opciones[$id]);
            }
        }
        return $opciones;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIdPadre() {
        return $this->idPadre;
    }

    public function setIdPadre($idPadre) {
        $this->idPadre = $idPadre;
    }

    public function getClave() {
        return $this->clave;
    }

    public function setClave($clave) {
        $this->clave = $clave;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function setTexto($texto) {
        $this->texto = $texto;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getOrden() {
        return $this->orden;
    }

    public function setOrden($orden) {
        $this->orden = $orden;
    }

    public function getHijos() {
        return $this->hijos;
    }

    public function setHijos($hijos) {
        $this->hijos = $hijos;
    }

    public function getNivel() {
        return $this->nivel;
    }

    public function setNivel($nivel) {
        $this->nivel = $nivel;
    }

}
?>
