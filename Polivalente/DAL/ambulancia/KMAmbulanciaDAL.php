<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class KMAmbulanciaDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function getAll() {
        return $this->db->jsonBuilder()->query("select h.*,
        (select km.KmAnterior from ambulancia_km as km where km.HojaVidaId = h.HojaVidaId order by km.KmId DESC limit 1) as LastKm, 
        (select km.KMId from ambulancia_km as km where km.HojaVidaId = h.HojaVidaId order by km.KmId DESC limit 1) as KMId,
        (select km.Km from ambulancia_km as km where km.HojaVidaId = h.HojaVidaId order by km.KmId DESC limit 1) as Km,
        (select km.CreatedAt from ambulancia_km as km where km.HojaVidaId = h.HojaVidaId order by km.KmId DESC limit 1) as FechaUltimaActualizacion
        from ambulancia_hojavida as h where Estado= 'Activo';");
    }
    
    public function GetLastKMByHojaVidaId($HojaVidaId) {
        return $this->db->objectBuilder()->query("select * from ambulancia_reporte where HojaVidaId = $HojaVidaId order by ReporteId DESC limit 1;");
    }
    
    public function GetHistoriaKM($From, $To) {
        return $this->db->objectBuilder()->query("SELECT k.CreatedAt,k.CreatedBy,k.HojaVidaId,k.KM,k.KMId, h.Placa
            FROM ambulancia_km as k
            inner join ambulancia_hojavida as h on k.HojaVidaId = h.HojaVidaId
            where k.CreatedAt >= '$From' and k.CreatedAt <= '$To';");
    }

    public function CreateKM($list) {
        $ids = $this->db->insertMulti("ambulancia_km", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function GetKM($Nombre) {
        $this->db->where("Nombre", $Nombre);
        return $this->db->objectBuilder()->get("ambulancia_km");
    }
    
    public function DeleteKM($KMId) {
        $this->db->where("KMId", $KMId);
        return $this->db->objectBuilder()->delete("ambulancia_km");
    }
    
    public function UpdateKM($list, $id) {
        $this->db->where('KMId', $id);
        if ($this->db->update('ambulancia_km', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

}
