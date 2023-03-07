<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class EncabezadoDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function getAll() {
        return $this->db->jsonBuilder()->get("EncabezadoPiePagina");
    }
    
    public function CreateEncabezado($list) {
        $ids = $this->db->insertMulti("EncabezadoPiePagina", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function GetEncabezado() {
        return $this->db->objectBuilder()->getOne("EncabezadoPiePagina");
    }
    
    public function UpdateEncabezado($list, $id) {
        $this->db->where ('EncabezadoPiePaginaId', $id);
        if ($this->db->update('EncabezadoPiePagina', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
