<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class MedicamentoDAL {

    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
       $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateMedicamento($list) {
        $ids = $this->db->insertMulti("cm_medicamento", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function UpdateMedicamento($list, $id) {
        $this->db->where('MedicamentoId', $id);
        if ($this->db->update('cm_medicamento', $list[0])) {
            return $list;
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function GetMedicamentos() {
        return $this->db->objectBuilder()->rawQuery("select CodigoKrystalos, MedicamentoId from cm_medicamento where PrecioCompra is null;");
    }
    
    public function GetMedicamentosByTipoMedicamentoId($TipoMedicamentoId) {
        return $this->db->objectBuilder()->rawQuery("select * from cm_medicamento where TipoMedicamentoId = $TipoMedicamentoId and IsInRonda = true order by Orden;");
    }
    
    public function GetMedicamentosByTipoMedicamentoId_Lite($TipoMedicamentoId) {
        return $this->db->objectBuilder()->rawQuery("select MedicamentoId, NombreAbreviado, FormaFarmaceutica, Concentracion from cm_medicamento where TipoMedicamentoId = $TipoMedicamentoId and FormaFarmaceutica = 'RECONSTITUYENTE' and IsInRonda = true order by Orden;");
    }
    
    public function GetMedicamentosByTipoMedicamentoId_Lite_Loteado($TipoMedicamentoId) {
        return $this->db->objectBuilder()->rawQuery("select MedicamentoId, NombreAbreviado, FormaFarmaceutica, CanRecostInLoteado, TipoMedicamentoId, IsMiniBag, Concentracion from cm_medicamento where TipoMedicamentoId = $TipoMedicamentoId and FormaFarmaceutica = 'RECONSTITUYENTE' and IsLoteado = true order by Orden;");
    }
    
    public function GetMedicamentoById($MedicamentoId) {
        return $this->db->objectBuilder()->rawQuery("select * from cm_medicamento where MedicamentoId = $MedicamentoId order by Orden;");
    }
    
    public function GetMedicamentos_Lite($TipoMedicamento) {
        return $this->db->objectBuilder()->rawQuery("select * from cm_medicamento where FormaFarmaceutica <> 'RECONSTITUYENTE' and TipoMedicamento = '$TipoMedicamento' order by Orden;");
    }
    
}