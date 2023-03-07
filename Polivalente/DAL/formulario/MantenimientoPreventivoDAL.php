<?php
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class MantenimientoPreventivoDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function GetAllMantenimientoPreventivosByServicio($ServicioId, $Year) {
        return $this->db->objectBuilder()->rawQuery("SELECT h.HojaVidaId, h.Marca, h.Modelo,h.Equipo, r.Responsable,f.Nombre as Frecuencia, 
        IF(h.FechaInstalacion IS NULL,concat(1,'/01/$Year') ,h.FechaInstalacion)
		as FechaMantenimientoPreventivo1, IF(r.Fecha IS NULL,h.FechaInstalacion, r.Fecha)  as FechaMantenimientoPreventivo2, r.TipoReporte, r.ReporteId, r.ReporteArchivo,1 as Inicio,
        h.Serie, s.Nombre as Servicio, h.Ubicacion, f.Nombre as Frecuencia 
        FROM hojavida as h
        STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
        STRAIGHT_JOIN frecuenciamantenimiento as f on h.FrecuenciaMantenimientoId = f.FrecuenciaMantenimientoId
        STRAIGHT_JOIN reporte as r on h.HojaVidaId = r.EquipoId and GetLastReporteid(h.HojaVidaId,$Year) = r.ReporteId
        where h.ServicioId = $ServicioId and f.Nombre <> 'NO APLICA' and h.Estado = 'Activo';");
    }
    
    public function GetAllMantenimientoPreventivos($SedeId) {
        $d = date("Y");
        return $this->db->objectBuilder()->rawQuery("SELECT h.HojaVidaId, h.Marca, h.Modelo,h.Equipo, r.Responsable,f.Nombre as Frecuencia,
        IF(h.FechaInstalacion IS NULL,concat(1,'/01/$d') ,h.FechaInstalacion)
		as FechaMantenimientoPreventivo1, r.Fecha as FechaMantenimientoPreventivo2, r.TipoReporte, r.ReporteId, r.ReporteArchivo,1 as Inicio,
        h.Serie, s.Nombre as Servicio, h.Ubicacion, f.Nombre as Frecuencia,h.SedeId  
        FROM hojavida as h
        STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
        STRAIGHT_JOIN frecuenciamantenimiento as f on h.FrecuenciaMantenimientoId = f.FrecuenciaMantenimientoId
        STRAIGHT_JOIN reporte as r on h.HojaVidaId = r.EquipoId and GetLastReporteid(h.HojaVidaId,$d) = r.ReporteId
        where h.SedeId = $SedeId and f.Nombre <> 'NO APLICA' and h.Estado = 'Activo';");
    }
    
    public function GetNEquiposByServicio($UsuarioId){
        return $this->db->objectBuilder()->rawQuery("select count(h.HojaVidaId) as Total from hojavida as h 
        STRAIGHT_JOIN serviciousuario as su on su.UsuarioId = $UsuarioId
        where h.ServicioId = su.ServicioId and h.Estado = 'Activo'");
    }
}
