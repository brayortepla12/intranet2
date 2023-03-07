<?php

include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class PacienteDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

 
    
    public function GetPacienteByCodigoQR($CodigoQR) {
//        return "SELECT * FROM polivalente.hemodinamia_paciente where Documento = '$Documento';";
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM Hemodinamia_Paciente where CodigoQR = '$CodigoQR';");
    }
    
    public function CountHojaVidasBySede($SedeId) {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM sistemas_hojavida where Estado = 'Activo' and SedeId = $SedeId;");
    }
    
    public function CreatePaciente($list) {
        $ids = $this->db->insertMulti("Hemodinamia_Paciente", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
}
