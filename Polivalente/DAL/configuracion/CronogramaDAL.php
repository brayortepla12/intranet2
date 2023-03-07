<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class CronogramaDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function getAll() {
        return $this->db->jsonBuilder()->query("SELECT c.CronogramaId, c.Inicio, c.FrecuenciaMantenimientoId, c.HojaVidaId, c.ModifiedAt, c.ModifiedBy, c.Nombre, c.SedeId,c.ServicioId,c.CreatedAt,c.CreatedBy,
            h.Equipo, s.Nombre as Sede, ser.Nombre as Servicio, f.Nombre as Frecuencia FROM Cronograma as c
            inner join sede as s on c.SedeId = s.SedeId
            inner join hojavida as h on c.HojaVidaId = h.HojaVidaId
            inner join servicio as ser on h.ServicioId = ser.ServicioId
            inner join frecuenciamantenimiento as f on c.FrecuenciaMantenimientoId = f.FrecuenciaMantenimientoId order by c.CronogramaId desc;");
    }

    public function CreateCronograma($list) {
        $ids = $this->db->insertMulti("Cronograma", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function GetCronograma($Nombre) {
        $this->db->where("Nombre", $Nombre);
        return $this->db->objectBuilder()->get("Cronograma");
    }
    
    public function DeleteCronograma($CronogramaId) {
        $this->db->where("CronogramaId", $CronogramaId);
        return $this->db->objectBuilder()->delete("Cronograma");
    }
    
    public function UpdateCronograma($list, $id) {
        $this->db->where('CronogramaId', $id);
        if ($this->db->update('Cronograma', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

}
