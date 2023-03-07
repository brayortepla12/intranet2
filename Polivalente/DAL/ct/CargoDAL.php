<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class CargoDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function GetCargoById($CargoId) {
        return $this->db->objectBuilder()->rawQuery("Select CargoId, Cargo from ct_cargo where CargoId = $CargoId");
    }
    
    public function GetCargos() {
        return $this->db->objectBuilder()->rawQuery("Select CargoId, Cargo from ct_cargo");
    }
}


