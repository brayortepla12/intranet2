<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class LogEmailDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }


    public function getAll() {
        return $this->db->objectBuilder()->query("SELECT * from Autorizacion_LogEmail where Estado <> 'Inactivo' order by Nombre desc;");
    }
    
    public function GetAllByPlantilla($ServicioId, $UserId) {
        return $this->db->objectBuilder()->query("SELECT a.*, rc.RelacionCostoId , rc.DiasConsumo, rc.Cantidad from almacen_LogEmail as a 
        inner join almacen_relacioncosto as rc on a.LogEmailId = rc.LogEmailId
        where rc.ServicioId = $ServicioId and rc.UsuarioId = $UserId and rc.Estado <> 'Inactivo' order by Nombre;");
    }
    
    public function getAllByUserId($UserId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM almacen_relacioncosto as rc 
        inner join usuario as u on rc.UsuarioId = u.UsuarioId
        inner join servicio as ser on rc.ServicioId = ser.ServicioId
        where u.UsuarioId = $UserId;");
    }
    
    public function CreateLogEmail($list) {
        $ids = $this->db->insertMulti("Autorizacion_LogEmail", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateItemProtocoloLogEmail($list) {
        $ids = $this->db->insertMulti("LogEmail_ItemProtocolo", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function UpdateLogEmail($list, $id) {
        $this->db->where ('LogEmailId', $id);
        if ($this->db->update('Almacen_LogEmail', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
