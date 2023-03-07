<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class PermisoDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
       $dotenv->safeLoad();
       $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
       $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function getAll() {
        return $this->db->objectBuilder()->query("SELECT p.PermisoId,p.Tipo,p.State,p.label,p.ModuloId,m.Nombre as Modulo, p.Color FROM permiso as p
inner join modulo as m on p.ModuloId = m.ModuloId;");
    }
    public function getAllByUserId($UserId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM usuariopermiso as us "
                . "inner join permiso as p on us.PermisoId = p.PermisoId "
                . "where UsuarioId = $UserId;");
    }
    
    public function GetAllByLiderUsuarioId($UserId) {
        return $this->db->objectBuilder()->rawQuery("SELECT p.PermisoId,p.Tipo,p.State,p.label,p.ModuloId,m.Nombre as Modulo, p.Color FROM usuariopermiso as us 
                inner join permiso as p on us.PermisoId = p.PermisoId 
                inner join modulo as m on p.ModuloId = m.ModuloId
                where us.UsuarioId = $UserId;");
    }
    
    public function CreatePermiso($list) {
        $ids = $this->db->insertMulti("permiso", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function AsignarPermisoUsuario($list) {
        $ids = $this->db->insertMulti("usuariopermiso", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function RemoverPermisoUsuario($PermisoId,$UsuarioId) {
        $this->db->query("DELETE FROM usuariopermiso WHERE PermisoId=$PermisoId and UsuarioId=$UsuarioId");
    }
    
    public function GetPermiso($State) {
        $this->db->where("State", $State);
        return $this->db->objectBuilder()->get("Permiso");
    }
    
    public function GetUsuarioPermisoById($PermisoId, $UsuarioId) {
        $this->db->where("PermisoId", $PermisoId);
        $this->db->where("UsuarioId", $UsuarioId);
        return $this->db->objectBuilder()->getOne("usuariopermiso");
    }
    
    public function UpdatePermiso($list, $id) {
        $this->db->where ('PermisoId', $id);
        if ($this->db->update('permiso', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
