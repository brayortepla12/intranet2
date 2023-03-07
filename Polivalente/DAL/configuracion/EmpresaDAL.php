<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class EmpresaDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function getAll() {
        return $this->db->jsonBuilder()->get("empresa");
    }
    
    public function CreateEmpresa($list) {
        $ids = $this->db->insertMulti("empresa", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function GetEmpresa() {
        return $this->db->objectBuilder()->getOne("empresa");
    }
    
    public function UpdateEmpresa($list, $id) {
        $this->db->where ('EmpresaId', $id);
        if ($this->db->update('empresa', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
