<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class GrupoDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function getAll() {
        return $this->db->jsonBuilder()->get("Almacen_Grupo");
    }
    public function getAllByUserId($UserId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT DISTINCT se.GrupoId, se.Nombre FROM serviciousuario as su
                inner join servicio as s on su.ServicioId = s.ServicioId
                inner join sede as se on s.GrupoId = se.GrupoId
                where su.UsuarioId = $UserId;");
    }
    public function IsInEnfermeria($UserId, $Enfermeria = 6) {
        return $this->db->objectBuilder()->rawQuery("SELECT gu.GrupoUsuarioId FROM polivalente.thc_grupousuario as gu
        where gu.UsuarioId = $UserId and gu.GrupoId = $Enfermeria; ");
    }
    
    public function CreateGrupo($list) {
        $ids = $this->db->insertMulti("Almacen_Grupo", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateGrupoUsuario($list) {
        $ids = $this->db->insertMulti("GrupoUsuario", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function GetGrupo($Nombre) {
        $this->db->where("Nombre", $Nombre);
        return $this->db->objectBuilder()->get("Almacen_Grupo");
    }
    
    public function GetGrupoByUsuario($GrupoId, $UsuarioId) {
        $this->db->where("GrupoId", $GrupoId);
        $this->db->where("UsuarioId", $UsuarioId);
        return $this->db->objectBuilder()->get("GrupoUsuario");
    }
    
    public function GetGrupoByGrupoId($GrupoId) {
        $this->db->where("GrupoId", $GrupoId);
        return $this->db->objectBuilder()->getOne("Almacen_Grupo");
    }
    
    public function UpdateGrupo($list, $id) {
        $this->db->where ('GrupoId', $id);
        if ($this->db->update('Grupo', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
