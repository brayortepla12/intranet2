<?php

include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class HistoriaClinicaDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    
    public function __destruct()
    {
       $this->db->disconnect();
    }
    public function GetHistoriaClinicaByPacienteId($PacienteId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM Hemodinamia_HistoricaClinica where PacienteId = $PacienteId order by HistoriaClinicaId DESC;");
    }
    
    public function GetHistoriaClinicaById($HistoriaClinicaId) {
//        return "SELECT * FROM Hemodinamia_HistoricaClinica where HistoriaClinicaId = $HistoriaClinicaId;";
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM Hemodinamia_HistoricaClinica where HistoriaClinicaId = $HistoriaClinicaId;");
    }
    
    public function CreateHistoriaClinica($list) {
        $ids = $this->db->insertMulti("Hemodinamia_HistoricaClinica", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
}
