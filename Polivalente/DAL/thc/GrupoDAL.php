<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

class GrupoDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct() {
        $this->db->disconnect();
    }

    public function GetGrupos() {
        return $this->db->objectBuilder()->rawQuery("select GrupoId, Nombre FROM thc_grupo where CanShow = 1");
    }

    public function GetUsuarioByGrupoId($GrupoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT u.UsuarioId, u.NombreCompleto 
        FROM thc_grupousuario as gu 
        inner join usuario as u on gu.UsuarioId = u.UsuarioId
        inner join thc_grupo as g on g.GrupoId = gu.GrupoId
        where g.GrupoId = $GrupoId;");
    }
    
    public function GetGrupoByUsuario($UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT g.GrupoId, g.Nombre 
        FROM thc_grupousuario as gu 
        inner join usuario as u on gu.UsuarioId = u.UsuarioId
        inner join thc_grupo as g on g.GrupoId = gu.GrupoId
        where u.UsuarioId = $UsuarioId;");
    }
}
