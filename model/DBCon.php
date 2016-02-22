<?php

/**
 * dbClass
 *
 * @author @author Agustin Arias <aarias@adoxweb.com.ar>
 */
class BDCon {

    private $con = null;
    private $dbParams = null;
    private $_results = array();

    public function __construct($dbParams) {
        $this->dbParams = $dbParams;
    }

    public function obtCon() {

        if ($this->getCon() == null) {
            $this->setCon(new PDO("mysql:host=" . $this->dbParams["HOST"] . ";dbname=" . $this->dbParams["BASE"], $this->dbParams["USR"], $this->dbParams["PASS"], array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)));
        }

        return $this->getCon();
    }

    public function select($query) {
        global $_ejecValida;

        $registros = false;

        if ($_ejecValida) {
            $this->obtCon();

            set_time_limit(0);

            try {
                $registros = $this->getCon()->query($query . " ;");
            } catch (PDOException $e) {
                logError("Problemas en query (" . $query . "): " . $e->getMessage());
            }

            $registros = $registros->fetchAll();
            $registros["cant"] = count($registros);
            $registros["index"] = 0;
            $registros["query"] = $query;
        }

        return $registros;
    }

    public function call($query, $forceQuery = true) {
        global $_ejecValida;

        $registros = false;

        if ($_ejecValida) {
            if ($this->_results[$query] && !$forceQuery)
                return $this->_results[$query];

            $this->obtCon();

            set_time_limit(0);

            logDebug($query);

            try {
                $registros = $this->getCon()->query(" CALL " . $query . " ;");
            } catch (PDOException $e) {
                logError("Problemas en procedure (" . $query . "): " . $e->getMessage());
            }

            $registros = $registros ? $registros->fetchAll() : array();
            $registros["cant"] = count($registros);
            $registros["index"] = 0;
            $registros["query"] = $query;
        }

        $this->_results[$query] = $registros;
        return $registros;
    }

    /**
     * Forma segura de ejecutar querys
     */
    public function stmt($stmt, $params, $forceQuery = true) {
        global $_ejecValida;
        $registros = false;
        $stmt = "CALL " . $stmt;
        logDebug($stmt);

        if ($_ejecValida) {
            if ($this->_results[$stmt] && !$forceQuery)
                return $this->_results[$stmt];
            $this->obtCon();
            set_time_limit(0);
            try {
                $pstmt = $this->getCon()->prepare($stmt);
                $pstmt->execute($params);
            } catch (PDOException $e) {
                logError("Problemas en prepared statement (" . $stmt . "): " . $e->getMessage());
            }
            foreach ($pstmt as $registro) {
                $registros[] = $registro;
            }
            $registros["cant"] = count($registros);
            $registros["index"] = 0;
            $registros["query"] = $stmt;
        }

        $this->_results[$stmt] = $registros;
        return $registros;
    }
    
    public function update($update) {
        global $_ejecValida;
//        logDebug($update);

        if ($_ejecValida) {
            $this->obtCon();
            try {
                $this->getCon()->query($update);
            } catch (PDOException $e) {
                logError("Problemas en update (" . $update . "): " . mysql_error());
                logError($e);
            }
        }
    }

    public function updatestmt($update, $params) {
        global $_ejecValida;
        $update = "CALL " . $update;
        logDebug($update);

        if ($_ejecValida) {
            $this->obtCon();
            try {
                $pstmt = $this->getCon()->prepare($update);
                $pstmt->execute($params);
            } catch (PDOException $e) {
                logError("Problemas en update (" . $update . "): " . mysql_error());
                logError($e);
            }
        }
    }
    
    public function insert($insert) {
        global $_ejecValida;
        logDebug($insert);

        if ($_ejecValida) {
            $this->obtCon();
            try {
                $this->getCon()->query($insert);
            } catch (PDOException $e) {
                logError("Problemas en insert (" . $insert . "): " . mysql_error());
            }
        }
    }

    public function fetch(&$registros) {
        global $_ejecValida;

        $res = false;

        if ($_ejecValida) {

            if ($registros && isset($registros[$registros["index"]]) && $registros["index"] < count($registros)) {
                $res = $registros[$registros["index"]];
                $registros["index"]++;
            }
        }
        return $res;
    }

    public function getCon() {
        return $this->con;
    }

    public function setCon($con) {
        $this->con = $con;
    }

}

?>
