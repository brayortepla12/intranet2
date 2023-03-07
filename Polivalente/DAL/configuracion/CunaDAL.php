<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class CunaDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }


    public function getAll() {
        return $this->db->jsonBuilder()->get("Observador_Cuna");
    }
    public function getAllByUserId($UserId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT DISTINCT se.CunaId, se.Nombre FROM serviciousuario as su
                inner join servicio as s on su.ServicioId = s.ServicioId
                inner join sede as se on s.CunaId = se.CunaId
                where su.UsuarioId = $UserId;");
    }
    
    public function getAllByToken($Token) {
        return $this->db->jsonBuilder()->rawQuery("SELECT * from Observador_Cuna as c 
                Inner join Observador_Token as t on c.CunaId = t.CunaId 
                where t.Token = '$Token'");
    }
    
    public function getAllByNombre($Nombre) {
        return $this->db->objectBuilder()->rawQuery("SELECT * from Observador_Cuna as c 
                where c.Nombre = '$Nombre'");
    }
    
    public function CreateCuna($list) {
        $ids = $this->db->insertMulti("Observador_Cuna", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateCunaUsuario($list) {
        $ids = $this->db->insertMulti("Observador_Cuna", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function GetCuna($Nombre) {
        $this->db->where("Nombre", $Nombre);
        return $this->db->objectBuilder()->get("Observador_Cuna");
    }
    
    public function GetCunaByUsuario($CunaId, $UsuarioId) {
        $this->db->where("CunaId", $CunaId);
        $this->db->where("UsuarioId", $UsuarioId);
        return $this->db->objectBuilder()->get("Observador_Cuna");
    }
    
    public function GetCunaByCunaId($CunaId) {
        $this->db->where("CunaId", $CunaId);
        return $this->db->objectBuilder()->getOne("Observador_Cuna");
    }
    
    public function UpdateCuna($list, $id) {
        $this->db->where ('CunaId', $id);
        if ($this->db->update('Observador_Cuna', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
