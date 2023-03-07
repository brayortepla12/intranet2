<?php

include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

class MantenimientoPreventivoSistemaDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct() {
        $this->db->disconnect();
    }

    public function GetAllMantenimientoPreventivoSistemasByServicio($ServicioId, $Year) {
        return $this->db->objectBuilder()->rawQuery("SELECT h.HojaVidaId, h.Fabricante as Marca, h.Modelo,h.Nombre as Equipo, r.Responsable,IF(f2.Nombre <> 'NO APLICA', f2.Nombre, f.Nombre) as Frecuencia, 
            r.TipoReporte, r.ReporteId, r.ReporteArchivo, r.Fecha,
            h.NSerial as Serie, s.Nombre as Servicio, h.Ubicacion, cs.CronogramaServicioId, cs.MesInicial
            FROM sistemas_hojavida as h
            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
            left join sistemas_reporte as r on h.HojaVidaId = r.EquipoId and GetLastReporteidSistemas(h.HojaVidaId,$Year) = r.ReporteId
            STRAIGHT_JOIN sistemas_cronogramaservicio as cs on h.ServicioId = cs.ServicioId
            STRAIGHT_JOIN frecuenciamantenimiento as f on cs.FrecuenciaMantenimientoId = f.FrecuenciaMantenimientoId
            STRAIGHT_JOIN frecuenciamantenimiento as f2 on h.FrecuenciaMantenimientoId = f2.FrecuenciaMantenimientoId
            where f.Nombre <> 'NO APLICA' and f2.Nombre <> 'NO APLICA' and h.Estado = 'Activo' and cs.Vigencia = $Year;");
    }

    public function GetAllMantenimientoPreventivoSistemassBySedeVersion2($SedeId, $ServicioId, $Year) {
        return $this->db->objectBuilder()->rawQuery("select h.HojaVidaId, h.Nombre as Equipo, h.Ubicacion, h.FrecuenciaMantenimientoId, sed.Nombre as Sede, 
        f.Nombre as Frecuencia, s.Nombre as Servicio, s.ServicioId, 
        dc1.Mes as '1', GetReporteIdByDetalleCronograma(dc1.DetalleCronogramaId, h.HojaVidaId) as 'r1',
        dc2.Mes as '2', GetReporteIdByDetalleCronograma(dc2.DetalleCronogramaId, h.HojaVidaId) as 'r2', 
        dc3.Mes as '3', GetReporteIdByDetalleCronograma(dc3.DetalleCronogramaId, h.HojaVidaId) as 'r3', 
        dc4.Mes as '4', GetReporteIdByDetalleCronograma(dc4.DetalleCronogramaId, h.HojaVidaId) as 'r4', 
        dc5.Mes as '5', GetReporteIdByDetalleCronograma(dc5.DetalleCronogramaId, h.HojaVidaId) as 'r5', 
        dc6.Mes as '6', GetReporteIdByDetalleCronograma(dc6.DetalleCronogramaId, h.HojaVidaId) as 'r6', 
        dc7.Mes as '7', GetReporteIdByDetalleCronograma(dc7.DetalleCronogramaId, h.HojaVidaId) as 'r7', 
        dc8.Mes as '8', GetReporteIdByDetalleCronograma(dc8.DetalleCronogramaId, h.HojaVidaId) as 'r8', 
        dc9.Mes as '9', GetReporteIdByDetalleCronograma(dc9.DetalleCronogramaId, h.HojaVidaId) as 'r9', 
        dc10.Mes as '10', GetReporteIdByDetalleCronograma(dc10.DetalleCronogramaId, h.HojaVidaId) as 'r10', 
        dc11.Mes as '11', GetReporteIdByDetalleCronograma(dc11.DetalleCronogramaId, h.HojaVidaId) as 'r11', 
        dc12.Mes as '12', GetReporteIdByDetalleCronograma(dc12.DetalleCronogramaId, h.HojaVidaId) as 'r12'
        from sistemas_hojavida as h
        STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId and s.SedeId = $SedeId
        STRAIGHT_JOIN sistemas_detallecronograma as dc on s.ServicioId = dc.ServicioId
        left join sistemas_detallecronograma as dc1 on s.ServicioId = dc1.ServicioId and dc1.Mes = 1
        left join sistemas_detallecronograma as dc2 on s.ServicioId = dc2.ServicioId and dc2.Mes = 2
        left join sistemas_detallecronograma as dc3 on s.ServicioId = dc3.ServicioId and dc3.Mes = 3
        left join sistemas_detallecronograma as dc4 on s.ServicioId = dc4.ServicioId and dc4.Mes = 4
        left join sistemas_detallecronograma as dc5 on s.ServicioId = dc5.ServicioId and dc5.Mes = 5
        left join sistemas_detallecronograma as dc6 on s.ServicioId = dc6.ServicioId and dc6.Mes = 6
        left join sistemas_detallecronograma as dc7 on s.ServicioId = dc7.ServicioId and dc7.Mes = 7
        left join sistemas_detallecronograma as dc8 on s.ServicioId = dc8.ServicioId and dc8.Mes = 8
        left join sistemas_detallecronograma as dc9 on s.ServicioId = dc9.ServicioId and dc9.Mes = 9
        left join sistemas_detallecronograma as dc10 on s.ServicioId = dc10.ServicioId and dc10.Mes = 10
        left join sistemas_detallecronograma as dc11 on s.ServicioId = dc11.ServicioId and dc11.Mes = 11
        left join sistemas_detallecronograma as dc12 on s.ServicioId = dc12.ServicioId and dc12.Mes = 12
        STRAIGHT_JOIN sistemas_cronograma as c on c.CronogramaId = dc.CronogramaId
        STRAIGHT_JOIN sede as sed on sed.SedeId = $SedeId
        STRAIGHT_JOIN frecuenciamantenimiento as f on h.FrecuenciaMantenimientoId
        where c.Vigencia = $Year  and (('Todos' = '$ServicioId') or s.ServicioId = '$ServicioId') group by s.ServicioId, h.HojaVidaId order by s.Nombre, h.Nombre");
    }

    public function GetAllMantenimientoPreventivoSistemassByServicioVersion2($ServicioId, $Year) {
        return $this->db->objectBuilder()->rawQuery("SELECT h.HojaVidaId, h.Fabricante as Marca, h.Modelo,h.Nombre as Equipo, r.Responsable, IF(f2.Nombre <> 'NO APLICA', f2.Nombre, f.Nombre) as Frecuencia, 
            r.TipoReporte, r.ReporteId, r.ReporteArchivo, r.Fecha,
            h.NSerial as Serie, s.Nombre as Servicio, h.Ubicacion, cs.CronogramaServicioId, cs.MesInicial
            FROM sistemas_hojavida as h
            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
            left join sistemas_reporte as r on h.HojaVidaId = r.EquipoId and GetLastReporteidSistemas(h.HojaVidaId,$Year) = r.ReporteId
            STRAIGHT_JOIN sistemas_cronogramaservicio as cs on h.ServicioId = cs.ServicioId
            STRAIGHT_JOIN frecuenciamantenimiento as f on cs.FrecuenciaMantenimientoId = f.FrecuenciaMantenimientoId
            STRAIGHT_JOIN frecuenciamantenimiento as f2 on h.FrecuenciaMantenimientoId = f2.FrecuenciaMantenimientoId
            where f.Nombre <> 'NO APLICA' and h.Estado = 'Activo' and cs.Vigencia = $Year;");
    }

    public function GetAllMantenimientoPreventivoSistemas($SedeId) {
        $Year = date("Y");
        return $this->db->objectBuilder()->rawQuery("select h.HojaVidaId, h.Nombre as Equipo, h.Ubicacion, h.FrecuenciaMantenimientoId, 
        f.Nombre as Frecuencia, s.Nombre as Servicio, s.ServicioId, s.SedeId, h.Fabricante as Marca, h.Modelo, h.NSerial as Serie, LPAD( dc.Mes, 2, '0' ) as Mes, '$Year' as Year  
        from servicio as s
        STRAIGHT_JOIN sistemas_detallecronograma as dc on s.ServicioId = dc.ServicioId
        STRAIGHT_JOIN sistemas_cronograma as c on c.CronogramaId = dc.CronogramaId
        STRAIGHT_JOIN sede as sed on sed.SedeId = $SedeId
        STRAIGHT_JOIN sistemas_hojavida as h on h.ServicioId = s.ServicioId and s.SedeId = sed.SedeId
        STRAIGHT_JOIN frecuenciamantenimiento as f on h.FrecuenciaMantenimientoId
        where dc.Mes <= month(now()) and GetReporteIdByDetalleCronograma(dc.DetalleCronogramaId, h.HojaVidaId) is null
        group by s.ServicioId, h.HojaVidaId order by s.Nombre, h.Nombre;");
    }

    public function GetNEquiposByServicio($UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("select count(h.HojaVidaId) as Total from sistemas_hojavida as h 
        STRAIGHT_JOIN serviciousuario as su on su.UsuarioId = $UsuarioId
        where h.ServicioId = su.ServicioId and h.Estado = 'Activo'");
    }

}
