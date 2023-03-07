<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class DispositivoDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    
    public function GetDispositivoById($DispositivoId) {
        return $this->db->objectBuilder()->rawQuery("Select DispositivoId, Dispositivo from ct_dispositivo where DispositivoId = $DispositivoId");
    }
            
    public function GetDispositivoByName($Nombre) {
        return $this->db->objectBuilder()->rawQuery("Select DispositivoId, Dispositivo from ct_dispositivo where Dispositivo='$Nombre'");
    }
    public function GetDispositivos() {
        return $this->db->objectBuilder()->rawQuery("Select DispositivoId, Dispositivo from ct_dispositivo");
    }
}


