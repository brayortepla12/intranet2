<?php
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class CalibracionDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function GetAllCalibracionesByServicio($ServicioId, $Year) {
        return $this->db->objectBuilder()->rawQuery("SELECT h.HojaVidaId, h.Marca, h.Modelo,h.Equipo, r.Responsable,f.Nombre as Frecuencia, 
        concat(c.Inicio,'/01/$Year') as FechaCalibracion1, r.Fecha as FechaCalibracion2, r.TipoReporte, r.ReporteId, r.ReporteArchivo,
            c.Inicio,
        h.Serie, s.Nombre as Servicio, h.Ubicacion, f.Nombre as Frecuencia 
        FROM cronograma as c
        STRAIGHT_JOIN hojavida as h on c.HojaVidaId = h.HojaVidaId
        STRAIGHT_JOIN servicio as s on c.ServicioId = s.ServicioId
        STRAIGHT_JOIN frecuenciamantenimiento as f on c.FrecuenciaMantenimientoId = f.FrecuenciaMantenimientoId
        left join reporte as r on h.HojaVidaId = r.EquipoId and (SELECT max(reporteid) FROM reporte where TipoServicio = 'CALIBRACION' and YEAR(Fecha) = '$Year') = r.ReporteId 
        where c.ServicioId = '$ServicioId' and c.Nombre = 'CALIBRACION' and f.Nombre <> 'NO APLICA';");
    }
    
    public function GetAllCalibraciones($SedeId) {
        $d = date("Y");
        return $this->db->objectBuilder()->rawQuery("SELECT h.HojaVidaId, h.Marca, h.Modelo,h.Equipo, r.Responsable,f.Nombre as Frecuencia, se.Nombre as Sede, se.SedeId,
            concat(c.Inicio,'/01/$d') as FechaCalibracion1, r.Fecha as FechaCalibracion2, r.TipoReporte, r.ReporteId, r.ReporteArchivo,
            c.Inicio,
            h.Serie, s.Nombre as Servicio, h.Ubicacion
            FROM cronograma as c
        STRAIGHT_JOIN hojavida as h on c.HojaVidaId = h.HojaVidaId
        STRAIGHT_JOIN servicio as s on c.ServicioId = s.ServicioId
        STRAIGHT_JOIN sede as se on s.SedeId = se.SedeId
        STRAIGHT_JOIN frecuenciamantenimiento as f on c.FrecuenciaMantenimientoId = f.FrecuenciaMantenimientoId
            left join reporte as r on h.HojaVidaId = r.EquipoId and GetLastReporteidByHojaVidaIdCalibracion(h.HojaVidaId) = r.ReporteId
            where f.Nombre <> 'NO APLICA' and c.SedeId = $SedeId and c.Nombre = 'CALIBRACION';");
    }
}
