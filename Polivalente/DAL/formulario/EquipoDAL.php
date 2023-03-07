<?php
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class EquipoDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function GetAllEquipoes($ServicioId, $Year) {
        return $this->db->objectBuilder()->rawQuery("SELECT h.HojaVidaId, h.Marca, h.Modelo,h.Equipo, r.Responsable,
            h.Serie, s.Nombre as Servicio, h.Ubicacion
            FROM hojavida as h
            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
            left join reporte as r on h.HojaVidaId = r.EquipoId and (SELECT max(reporteid) FROM reporte where YEAR(Fecha) = '$Year') = r.ReporteId  
            where h.ServicioId = '$ServicioId';");
    }
    
    public function GetAllPlantasBySedeId($SedeId) {
        return $this->db->objectBuilder()->rawQuery("SELECT h.HojaVidaId as EquipoId, h.Marca, h.Modelo,h.Equipo,h.SedeId,
            h.Serie, s.Nombre as Servicio,h.ServicioId, h.Ubicacion
            FROM hojavida as h
            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId and s.Nombre = 'PLANTAS ELÉCTRICAS'
            where h.SedeId = $SedeId;");
    }
}
