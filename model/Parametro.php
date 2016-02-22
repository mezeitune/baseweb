<?php

/**
 * Parametro
 *
 * @author @author Agustin Arias <aarias@adoxweb.com.ar>
 */
class Parametro {

    private $id;
    private $valor;
    private $descripcion;

    const FROM = "FROM_MAILS";
    
    function __construct($rs = false) {
        if ($rs) {
            $this->id = $rs["id"];
            $this->valor = $rs["valor"];
            $this->descripcion = $rs["descripcion"];
        }
    }

    public function act($BD) {
        $BD->update("CALL actualizarParametro('" . $this->id . "','" . $this->valor . "')");
    }

    public static function obt($id, $BD = false) {
        global $params;
        $ret = "";
        
        if (!$params[$id]) {
            if($BD)
                $ret = new Parametro($BD->fetch($BD->call("obtParametro('$id')")));
        } else {
            $ret = $params[$id];
        }

        return $ret;
    }

    public static function obtV($id, $BD = false) {
        return self::obt($id, $BD)->getValor();
    }
    
    public static function obtTodos($BD) {
        return self::obtMultiples("obtParametros", $BD);
    }

    public static function obtMultiples($query, $BD) {

        $rs = $BD->call($query);
        $parametros = array();

        while ($res = $BD->fetch($rs)) {
            $parametro = new Parametro($res);
            $parametros[$parametro->getId()] = $parametro;
        }

        return $parametros;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

}

?>
