<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class ModuloDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }


    public function GetModulos(){
        $this->db->orderBy("Nombre", "ASC");
        return $this->db->jsonBuilder()->get("modulo");
    }
    
    public function GetModulosByUserId($UserId){
        return $this->db->jsonBuilder()->rawQuery("select m.ModuloId, m.codigo_icono, m.Nombre, p.State, m.img from modulo as m
        STRAIGHT_JOIN permiso as p on p.ModuloId = m.ModuloId
        STRAIGHT_JOIN usuariopermiso as up on up.PermisoId = p.PermisoId
        STRAIGHT_JOIN usuario as u on up.UsuarioId = u.UsuarioId
        where up.UsuarioId = $UserId group by m.ModuloId;");
    }
    
    public function GetModulosByLiderUsuarioId($UserId){
        return $this->db->jsonBuilder()->rawQuery("select distinct m.* from usuariopermiso as mu
        STRAIGHT_JOIN permiso as p on mu.PermisoId = p.PermisoId
        STRAIGHT_JOIN modulo as m on p.ModuloId = m.ModuloId
        where mu.UsuarioId = $UserId;");
    }
    
    public function GetById($id){
        $this->db->where ("ModuloId", $id);
        return $this->db->jsonBuilder()->getOne("modulo");
    }
    
    public function AsignarModuloUsuario($list) {
        $ids = $this->db->insertMulti("modulousuario", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
}
