<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";
class CIDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
       $dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
       $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function GetDCIByVigencia($Vigencia) {
        return $this->db->objectBuilder()->query("SELECT dci.ServicioId, dci.Mes, dci.CIId FROM pol_detalleci as dci 
        inner join pol_ci as ci on dci.CIId = ci.CIId
        where ci.Vigencia = $Vigencia and ci.Estado = 'Activo';");
    }
    
    public function GetServiciosIdArr($CIId) {
        $this->db->where("CIId", $CIId);
        return $this->db->getValue("pol_detalleci", "ServicioId");
    }
    
    public function GetCIByVigencia($Vigencia) {
        return $this->db->objectBuilder()->query("SELECT ci.CIId FROM pol_ci as ci
        where ci.Vigencia = $Vigencia and ci.Estado = 'Activo';");
    }
    
    
    public function GetDCIByVigenciaAndServicioId($Vigencia, $ServicioId) {
        return $this->db->objectBuilder()->query("SELECT dci.* FROM pol_detalleci as dci 
        inner join pol_ci as ci on dci.CIId = ci.CIId
        where ci.Vigencia = $Vigencia and dci.ServicioId = $ServicioId and ci.Estado = 'Activo';");
    }

    public function GetItemsCI() {
        return $this->db->objectBuilder()->query("SELECT ItemCIId, Descripcion FROM polivalente.pol_itemci order by Descripcion;");
    }
    public function getAllByUserId($UserId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT DISTINCT se.CIId, se.Nombre FROM serviciousuario as su
        inner join servicio as s on su.ServicioId = s.ServicioId
        inner join sede as se on s.CIId = se.CIId
        where su.UsuarioId = $UserId;");
    }
    
    public function CreateCI($list) {
        $ids = $this->db->insertMulti("Pol_CI", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateDCI($list) {
        $ids = $this->db->insertMulti("pol_detalleci", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateCIUsuario($list) {
        $ids = $this->db->insertMulti("CIUsuario", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function GetCI($ServicioId) {
        $this->db->where("ServicioId", $ServicioId);
        return $this->db->objectBuilder()->get("CI");
    }
    
    public function GetCIByUsuario($CIId, $UsuarioId) {
        $this->db->where("CIId", $CIId);
        $this->db->where("UsuarioId", $UsuarioId);
        return $this->db->objectBuilder()->get("CIUsuario");
    }
    
    public function GetCIByCIId($CIId) {
        $this->db->where("CIId", $CIId);
        return $this->db->objectBuilder()->getOne("CI");
    }
    
    public function UpdateCI($list, $id) {
        $this->db->where ('CIId', $id);
        if ($this->db->update('CI', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
