<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class OrdenRRDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateOrdenRR($list) {
        $ids = $this->db->insertMulti("cm_OrdenRR", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function UpdateOrdenRR($list, $id) {
        $this->db->where('OrdenRRId', $id);
        if ($this->db->update('cm_OrdenRR', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function GetOrdenRRes() {
        return $this->db->jsonBuilder()->rawQuery("select * from cm_OrdenRR order by OrdenRRId desc;");
    }
    
    public function GetRondaById($OrdenRRId) {
        return $this->db->objectBuilder()->rawQuery("SELECT OrdenRRId, DATE_FORMAT(Fecha, '%d%m%y') as OrdenProduccion, Fecha FROM cm_rondaverificacion where OrdenRRId = $OrdenRRId;");
    }
    
    
    public function GetAllRondas_Lite() {
        return $this->db->objectBuilder()->rawQuery("select Fecha, OrdenRRId from cm_OrdenRR
        order by OrdenRRId");
    }

    public function VerificarDia($OrdenRRId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM cm_rondaverificacion 
        where OrdenRRId = $OrdenRRId and now() >= Fecha and now() <= DATE_ADD(Fecha, INTERVAL 1 DAY);");
    }

    public function VerificarDiaBySector($Sector, $TipoMedicamento) {
        return $this->db->objectBuilder()->rawQuery("SELECT o.* FROM cm_ordenrr as o
        STRAIGHT_JOIN cm_detalleordenrr as dorr on o.OrdenRRId = dorr.OrdenRRId
        where now() >= o.Fecha and now() <= DATE_ADD(o.Fecha, INTERVAL 1 DAY);");
    }
    
    public function GetOrdenRRByFecha($Fecha, $TipoMedicamento) {
        $this->db->objectBuilder()->rawQuery("SET @Contador3=0;");
        return $this->db->objectBuilder()->rawQuery("SELECT mes.Item as NumeroEnMes, orr.*, u.NombreCompleto as NombreDireccionTecnica, u.Firma as FirmaDT, u2.NombreCompleto as NombreAProduccion, u2.Firma as FirmaAP, u3.Firma as FirmaAF, u3.NombreCompleto as NombreAFarmacia 
        FROM cm_ordenrr as orr
        left join Usuario as u on orr.DireccionTecnicaId = u.UsuarioId
        left join Usuario as u2 on orr.AProduccionId = u2.UsuarioId
        left join Usuario as u3 on orr.AFarmaciaId = u3.UsuarioId
        STRAIGHT_JOIN (SELECT LPAD(@Contador3:=@Contador3 + 1, 3, '000') AS Item, OrdenRRId FROM cm_ordenrr 
        where MONTH(Fecha) = MONTH('$Fecha')) as mes on orr.OrdenRRId = mes.OrdenRRId
        where orr.Fecha = '$Fecha' and orr.TipoMedicamento = '$TipoMedicamento'");
    }

    public function GetOrdenRRById($OrdenRRId) {
        return $this->db->objectBuilder()->rawQuery("select * from cm_OrdenRR where OrdenRRId = $OrdenRRId;");
    }

}
