<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class ProtocoloDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function getAll() {
        $this->db->where("Estado", "Activo");
        return $this->db->objectBuilder()->get("pc_protocolo");
    }
    
    public function CreateProtocolo($list) {
        $ids = $this->db->insertMulti("pc_protocolo", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    
    public function GetProtocolo($Nombre) {
        $this->db->where("Nombre", $Nombre);
        return $this->db->objectBuilder()->get("pc_protocolo");
    }
    
    public function UpdateProtocolo($list, $id) {
        $this->db->where ('ProtocoloId', $id);
        if ($this->db->update('pc_protocolo', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
