<?php

/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class PermisoDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function getPermisos() {
        $this->db->orderBy("Nombre", "ASC");
        return $this->db->jsonBuilder()->get("permiso");
    }
    
    public function getPermisoByUser($UserId) {
//        $this->db->join("UsuarioPermiso up", "up.PermisoId=p.PermisoId");
//        $this->db->joinWhere("UsuarioPermiso up", "up.UsuarioId",$UserId);
//        return $this->db->jsonBuilder()->get("Permiso p");
        return $this->db->jsonBuilder()->query("select p.PermisoId,p.Tipo,p.State,p.label,p.ModuloId,up.UsuarioPermisoId, p.Color from permiso as p
        STRAIGHT_JOIN usuariopermiso as up on p.PermisoId = up.PermisoId
        where up.UsuarioId = $UserId and up.IsPH = 0
        union
        select p.PermisoId,p.Tipo,p.State,p.label,p.ModuloId,up.UsuarioPermisoId, p.Color from permiso as p
        STRAIGHT_JOIN usuariopermiso as up on p.PermisoId = up.PermisoId
        STRAIGHT_JOIN ct_persona as persona on persona.UsuarioIntranetId = up.UsuarioId
        STRAIGHT_JOIN ct_persona as jefe on jefe.UsuarioIntranetId = up.UOId
        where up.UsuarioId = $UserId and up.IsPH = 1 and persona.JefeId = jefe.PersonaId");
    }
}
