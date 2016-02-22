<?php

/**
 * Tipo
 *
 * @author jmarni
 */
class Tipo {

    private $id;
    private $descripcion;

    function __construct($rs = false) {
        if ($rs) {
            $this->id = $rs["id"];
            $this->descripcion = $rs["descripcion"];
        }
    }

    public static function obtMultiples($query, $BD) {

        $rs = $BD->call($query, false);
        $tipos = array();

        while ($res = $BD->fetch($rs)) {
            $tipo = new Tipo($res);
            $tipos[$tipo->getId()] = $tipo;
        }

        return $tipos;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

}

?>
