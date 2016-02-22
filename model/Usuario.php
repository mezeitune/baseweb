<?php

/**
 * 
 * @author @author Agustin Arias <aarias@adoxweb.com.ar>
 */
class Usuario {

    const COOKIE_ID = "UID";
    const COOKIE_HASH = "UHASH";
    
    const N_ADMIN = "A";

    private $id;
    private $username;
    private $pass;
    private $email;
    private $nivel;
    private $hash;
    private $logueado = false;
    private $cambiarPass = false;

    public function __construct($rs = false) {
        if ($rs) {
            $this->id = encrypt(strdecode($rs["id"]));
            $this->username = strdecode($rs["username"]);
            $this->email = strdecode($rs["email"]);
            $this->nivel = strdecode($rs["nivel"]);
        }
    }

    public function cargarSesion(BDCon $BD) {

        $id = isset($_COOKIE[self::COOKIE_ID]) ? decrypt($_COOKIE[self::COOKIE_ID]) : -1;
        $hash = isset($_COOKIE[self::COOKIE_HASH]) ? $_COOKIE[self::COOKIE_HASH] : "";

        $rs = $BD->stmt("verificarLogin(:id, :hash)", array("id" => ($id ? $id : -1), "hash" => $hash));

        if ($rs) {
            $usr = $BD->fetch($rs);
        } else {
            $usr = false;
        }

        if ($usr) {

            $this->id = encrypt($usr["id"]);
            $this->username = $usr["username"];
            $this->hash = $usr["hash"];
            $this->nivel = $usr["nivel"];
            $this->cambiarPass = $usr["cambiar_pass"];

            // Actualizo las cookies, le doy 3hs mas
            // Duración de la cookie.
            $expira = time() + 60 * 60 * 3;
            // Seteo la cookie de hash.
            setcookie(self::COOKIE_HASH, $this->hash, $expira, "/");
            // Seteo la cookie de ID.
            setcookie(self::COOKIE_ID, $this->id, $expira, "/");

            $this->logueado = true;
        } else {
            $this->logueado = false;
        }
        return $this->logueado;
    }

    public function login($username, $pass, $BD) {

        $ret = false;

        $passMD5 = md5($pass);

        $res = $BD->fetch($BD->stmt("login(:username, :pass_md5)", 
                array("username" => $username, "pass_md5" => $passMD5)));

        if (((int) $res["id"]) != 0) {
            $this->id = encrypt((int) $res["id"]);
            $this->username = $username;
            // Seteo el hash con el nombre de usuario y un el timestamp actual.
            $this->hash = md5(time() . $this->username);
            $BD->update("CALL confirmarLogin(" . decrypt($this->id) . ",'" 
                    . $this->hash . "')");
            // Duración de la cookie.
            $expira = time() + 60 * 60 * 3;
            // Seteo la cookie de hash.
            setcookie(self::COOKIE_HASH, $this->hash, $expira, "/");
            // Seteo la cookie de ID.
            setcookie(self::COOKIE_ID, $this->id, $expira, "/");

            $ret = true;
        } else {
            $ret = false;
        }

        $this->logueado = $ret;
        return $ret;
    }

    public function logueado() {
        return $this->logueado;
    }

    public function guardar(BDCon $BD) {

        $params["id"] = ($this->getId() ? decrypt($this->getId()) : "-1");
        $params["email"] = $this->getEmail();
        $params["username"] = $this->getUsername();
        $params["nivel"] = $this->getNivel();

        $rowUsuario = $BD->stmt("CALL guardarUsuario(:id, :email, :username, :nivel)", 
                array("id" => $this->id, "email" => $this->email,
            "username" => $this->username, "nivel" => $this->nivel));

        $idUsuario = encrypt($rowUsuario["id"]);

        $this->setId($idUsuario);

        return $idUsuario;
    }

    public function borrar($id, $BD) {
        $id = decrypt($id);
        $BD->update("CALL borrarUsuario($id)");
    }

    public static function obt($id, BDCon $BD) {

        $rs = $BD->fetch($BD->stmt("obtUsuario(:id)", 
                array("id" => decrypt($id))));

        return new Usuario($rs);
    }

    public static function obtPorNivel($nivel, $BD) {
        return self::obtMultiples("obtUsuariosNivel(:nivel)",
                array("nivel" => $nivel), $BD);
    }

    public static function obtMultiples($smtm, $params, BDCon $BD) {
        $rs = $BD->stmt($smtm, $params);

        $usuarios = array();

        while ($res = $BD->fetch($rs)) {

            $usuario = new Usuario($res);
            $usuarios[$usuario->getId()] = $usuario;
        }
        return $usuarios;
    }

    /**
     * Verifica si el usuario que está logueado tiene acceso a determinado nivel
     */
    public function tieneAcceso($nivel) {
        return !strstr($nivel, $this->nivel) ? false : true;
    }

    public function logout($BD) {
        // Limpio los valores en la base de datos

        $id = (isset($_COOKIE[self::COOKIE_ID]) ? decrypt($_COOKIE[self::COOKIE_ID]) : 0);
        $hash = isset($_COOKIE[self::COOKIE_HASH]) ? $_COOKIE[self::COOKIE_HASH] : "";

        if ($id != "" && $id != 0 && $hash != "" && $hash && $id)
            $BD->update("CALL logout(" . $id . ",'" . $hash . "')");

        // Borro las cookies
        // Hora en el pasado
        $expira = time() - 60 * 60 * 3;
        // Seteo la cookie de hash.
        setcookie(self::COOKIE_HASH, "", $expira, "/");
        // Seteo la cookie de ID.
        setcookie(self::COOKIE_ID, -1, $expira, "/");

        $this->logueado = false;

        return true;
    }

    static public function getNivelStr($niveles) {
        $str = "";
        foreach (str_split($niveles) as $nivel) {
            switch ($nivel) {
                case self::N_ADMIN:
                    $str = 'Administrador';
                    break;
            }
        }

        return $str;
    }

    public static function cambiarPass($id, $pass, $BD) {
        $id = decrypt($id);
        $BD->update("CALL cambiarPass($id, '$pass')");
    }

    public function resetearPass($username, $pass, BDCon $BD) {

        $rs = $BD->stmt("resetPass(:username, :pass_md5);", 
                array("username" => $username, "pass_md5" => md5($pass)));

        if ($rs) {
            $usr = $BD->fetch($rs);
        } else {
            $usr = false;
        }

        if ($usr) {
            $this->id = encrypt($usr["id"]);
            $this->username = $usr["username"];
            $this->email = $usr["email"];
            $this->nivel = $usr["nivel"];
        }
    }

    public function getId($encriptar = false) {
        if ($encriptar)
            return encrypt($this->id);
        else
            return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPass() {
        return $this->pass;
    }

    public function setPass($pass) {
        $this->pass = $pass;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getNivel() {
        return $this->nivel;
    }

    public function setNivel($nivel) {
        $this->nivel = $nivel;
    }

    public function getHash() {
        return $this->hash;
    }

    public function setHash($hash) {
        $this->hash = $hash;
    }

    public function getLogueado() {
        return $this->logueado;
    }

    public function setLogueado($logueado) {
        $this->logueado = $logueado;
    }

    public function getCambiarPass() {
        return $this->cambiarPass;
    }

    public function setCambiarPass($cambiarPass) {
        $this->cambiarPass = $cambiarPass;
    }

}

?>
