<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class VehiculoDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    
    public function __destruct()
    {
       $this->db->disconnect();
    }
    public function GetALLVehiculos() {
        return $this->db->objectBuilder()->rawQuery("select IF(Nombre = 'AGUA ESTERIL' or (Volumen is null or Concentracion is null), Nombre, IF(DispositivoMedicoId = 28, Concat('Minibag ',Nombre, ' ' , Concentracion, '% ' , Volumen, ' mL'), Concat(Nombre, ' ' , Concentracion, '% ' , Volumen, ' mL'))) as Nombre , OtroNombre, DispositivoMedicoId from cm_dispositivomedico order by Nombre;");
    }

    public function GetVehiculos() {
        return $this->db->objectBuilder()->rawQuery("select IF(Nombre = 'AGUA ESTERIL' or (Volumen is null or Concentracion is null), Nombre, IF(DispositivoMedicoId = 28, Concat('Minibag ',Nombre, ' ' , Concentracion, '% ' , Volumen, ' mL'), Concat(Nombre, ' ' , Concentracion, '% ' , Volumen, ' mL'))) as Nombre , OtroNombre, DispositivoMedicoId from cm_dispositivomedico where IsVehiculo = 1 order by Nombre;");
    }
    
    public function CreateDMByRonda($list) {
        $ids = $this->db->insertMulti("cm_DispositivoMedicoByRonda", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
}


