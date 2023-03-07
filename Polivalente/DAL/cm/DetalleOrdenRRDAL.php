<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class DetalleOrdenRRDAL {

    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateDetalleOrdenRR($list) {
        $ids = $this->db->insertMulti("cm_DetalleOrdenRR", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function UpdateDetalleOrdenRR($list, $id) {
        $this->db->where('DetalleOrdenRRId', $id);
        if ($this->db->update('cm_DetalleOrdenRR', $list)) {
            return $list;
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function GetDetalleOrdenRRByOrdenRRId($OrdenRRId) {
        $this->db->objectBuilder()->rawQuery("SET @Contador2=0;");
        return $this->db->objectBuilder()->rawQuery("SELECT LPAD(@Contador2:=@Contador2 + 1, 3, '000') AS Item, dorr.*, m.Nombre as Medicamento, 
        m.FormaFarmaceutica, m.Concentracion, m.Laboratorio,m.RegInvima, m.FechaLimiteUso2_8, m.FechaLimiteUso20_25, m.ViaAdministracion, dm.Nombre as DispositivoMedico 
        FROM cm_detalleordenrr as dorr
        STRAIGHT_JOIN cm_medicamento as m on dorr.MedicamentoId = m.MedicamentoId
        STRAIGHT_JOIN cm_dispositivomedico as dm on dorr.VehiculoId = dm.DispositivoMedicoId
        where OrdenRRId = $OrdenRRId;");
    }
}


