<?php

include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

class ReporteSistemaDAL {

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

    public function CreateRdc($list) {
//        echo print_r($list);
        $ids = $this->db->insertMulti("sistemas_reportedcronograma", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateReporte($list) {
//        echo print_r($list);
        $ids = $this->db->insertMulti("sistemas_reporte", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
//        $this->CreateRegRC($ids[0]);
        return $ids;
    }

    public function CreateEventoSolicitud($list)
    {
        $ids = $this->db->insertMulti("sistemas_eventosolicitud", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    /**
     * 
     * @param type $list
     * @return type 
     * CREAR REGISTRO DEL REPORTE Y SE ASOCIA AL CRONOGRAMA, PARA NOTIFICAR EL CUMPLIMIENTO DE ESTE.
     */
//    public function CreateRegRC($ReporteId) {
//        $this->db->objectBuilder()->rawQueryValue("INSERT INTO sistemas_reportedcronograma (ReporteId, DetalleCronogramaId, HojaVidaId)
//(select r.ReporteId, dc.DetalleCronogramaId, r.EquipoId from sistemas_detallecronograma as dc 
//STRAIGHT_JOIN sistemas_reporte as r on dc.ServicioId = r.ServicioId
//where r.ReporteId = $ReporteId and r.ReporteId not in (select rdc.ReporteId from sistemas_reportedcronograma as rdc) )limit 1;");
//    }

    public function FirmarReporteALL($PersonaRecibeId) {
        return $this->db->objectBuilder()->Query("UPDATE sistemas_reporte as r SET r.Estado = 'Firmado' where r.RecibeId = $PersonaRecibeId and r.Estado = 'Borrador';");
    }

    public function GetMesProximoEnCronograma($HojaVidaId) {
        return $this->db->objectBuilder()->rawQuery("select tabla.* from (select dc.*, rdc.ReporteId from sistemas_detallecronograma as dc 
        STRAIGHT_JOIN sistemas_hojavida as h on h.ServicioId = dc.ServicioId
        left join sistemas_reportedcronograma as rdc on dc.DetalleCronogramaId = rdc.DetalleCronogramaId
        left join sistemas_reporte as r on h.HojaVidaId = r.EquipoId and rdc.ReporteId = r.ReporteId
        where h.HojaVidaId = $HojaVidaId and r.ReporteId is null order by dc.Mes) tabla where tabla.ReporteId is null limit 1");
    }

    public function GetAllReportesByUsuarioServicio($UsuarioId, $SedeId, $ServicioId, $TipoServicio, $TipoArticulo) {
        return $this->db->jsonBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable, r.Fecha
                , h.Nombre as Equipo, h.Fabricante, h.Modelo, h.NSerial, h.SO, h.HojaVidaId, r.RecibeFecha, 
                r.RecibeHora,r.RecibeNombre, r.RecibeCargo, r.RecibeFirma, r.TipoServicio, r.Solicitante, r.CreatedBy, r.CreatedAt, r.Estado, r.Contador
                FROM sistemas_reporte as r
                STRAIGHT_JOIN usuario as u on $UsuarioId = u.UsuarioId
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                STRAIGHT_JOIN serviciousuario as su on u.UsuarioId = su.UsuarioId and su.ServicioId = ser.ServicioId
                left join sistemas_hojavida as h on r.EquipoId = h.HojaVidaId
                where r.EstadoReporte <> 'Inactivo' and (('$ServicioId' = 'TODOS' and r.SedeId = $SedeId ) or r.ServicioId = '$ServicioId') and ('$TipoServicio' = 'TODOS' or r.TipoServicio = '$TipoServicio') and ('$TipoArticulo' = 'TODOS' or h.TipoArticulo = '$TipoArticulo')
                order by r.ReporteId desc;");
    }

    public function GetAllReportesByUsuarioServicio_Autofirmar($UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Nombre, h.Fabricante, h.Modelo, h.NSerial, h.SO, h.HojaVidaId, r.RecibeFecha, 
                r.RecibeHora,r.RecibeNombre, r.RecibeCargo, r.RecibeFirma, r.TipoServicio, r.Solicitante, r.CreatedBy, r.CreatedAt, r.Estado, u2.Email as RecibeEmail 
                FROM sistemas_reporte as r
                STRAIGHT_JOIN usuario as u on $UsuarioId = u.UsuarioId
                STRAIGHT_JOIN usuario as u2 on r.RecibeNombre = u2.NombreCompleto
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                STRAIGHT_JOIN serviciousuario as su on u.UsuarioId = su.UsuarioId and su.ServicioId = ser.ServicioId
                left join sistemas_hojavida as h on r.EquipoId = h.HojaVidaId  
                and r.Estado = 'Borrador'
                order by r.ReporteId desc;");
    }

    public function GetAllReportesByUsuarioServicio_AutofirmarRecibeId($RecibeId) {
        return $this->db->objectBuilder()->rawQuery("select h.HojaVidaId, r.ReporteId, h.SedeId, h.FechaUltimoMantenimiento, h.FechaCalibracion, h.ServicioId, 
            h.FrecuenciaMantenimientoId, fm.Nombre as FrecuenciaMantenimiento, fc.Nombre as FrecuenciaCalibracion, 
            fc.FrecuenciaMantenimientoId as FrecuenciaCalibracionId,s.Nombre as Servicio, h.Ubicacion, 
            h.Nombre as Equipo, h.Fabricante as Proveedor, h.Modelo,h.NSerial, h.FechaInstalacion, h.Tipo, h.SO, h.SerieSO, 
            h.Foto,se.Nombre as Sede, h.TipoArticulo, h.RecomendacioneFabricante,h.Ram,h.Procesador,h.DiscoDuro,
			h.SedeId,h.ServicioId, r.Fecha, r.Solicitante, r.ResponsableCargo, u.Firma as RecibeFirma, u2.Firma as ResponsableFirma,
			h.Puerto, h.Contador, h.IP, h.IsSistema, h.LicenciaOffice, h.LicenciaWindows, h.LicenciaAntivirus, r.ResponsableNombre, 
			r.RecibeCargo, r.RecibeNombre, r.TipoServicio, r.FallaDetectada, r.EstadoFinal, r.RecibeHora, r.Recibefecha as RecibeFecha, r.RecibeId 
			FROM sistemas_reporte as r
            STRAIGHT_JOIN sistemas_hojavida as h on r.EquipoId = h.HojaVidaId
			STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
			STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
			STRAIGHT_JOIN frecuenciamantenimiento as fm on fm.FrecuenciaMantenimientoId = h.FrecuenciaMantenimientoId
			STRAIGHT_JOIN frecuenciamantenimiento as fc on fc.FrecuenciaMantenimientoId = h.FrecuenciaCalibracionId
                        left join ct_persona as p on r.RecibeId = p.PersonaId
                        left join ct_persona as pr on r.ResponsableId = pr.PersonaId
			where r.RecibeId = '$RecibeId' and  r.EstadoReporte <> 'Inactivo' and r.Estado <> 'Firmado'");
    }

    public function GetReporteByRecibeId($RecibeId) {
        return $this->db->objectBuilder()->rawQuery("select h.HojaVidaId, r.ReporteId, h.SedeId, h.FechaUltimoMantenimiento, h.FechaCalibracion, h.ServicioId, 
            h.FrecuenciaMantenimientoId, fm.Nombre as FrecuenciaMantenimiento, fc.Nombre as FrecuenciaCalibracion, 
            fc.FrecuenciaMantenimientoId as FrecuenciaCalibracionId,s.Nombre as Servicio, h.Ubicacion, 
            h.Nombre as Equipo, h.Fabricante as Proveedor, h.Modelo,h.NSerial, h.FechaInstalacion, h.Tipo, h.SO, h.SerieSO, 
            h.Foto,se.Nombre as Sede, h.TipoArticulo, h.RecomendacioneFabricante,h.Ram,h.Procesador,h.DiscoDuro,
            h.SedeId,h.ServicioId, r.Fecha, r.Solicitante, r.ResponsableCargo, 
            p.Firma as RecibeFirma, 
            pr.Firma as ResponsableFirma,
            h.Puerto, h.Contador, h.IP, h.IsSistema, h.LicenciaOffice, h.LicenciaWindows, h.LicenciaAntivirus, r.ResponsableNombre, 
            r.RecibeCargo, r.RecibeNombre, r.TipoServicio, r.FallaDetectada, r.EstadoFinal, r.RecibeHora, r.Recibefecha as RecibeFecha
            FROM sistemas_reporte as r
            STRAIGHT_JOIN sistemas_hojavida as h on r.EquipoId = h.HojaVidaId
            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
            STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
            STRAIGHT_JOIN frecuenciamantenimiento as fm on fm.FrecuenciaMantenimientoId = h.FrecuenciaMantenimientoId
            STRAIGHT_JOIN frecuenciamantenimiento as fc on fc.FrecuenciaMantenimientoId = h.FrecuenciaCalibracionId
            left join ct_persona as p on r.RecibeId = p.PersonaId
            left join ct_persona as pr on r.ResponsableId = pr.PersonaId
            where r.RecibeId = '$RecibeId' and  r.EstadoReporte <> 'Inactivo' and r.Estado <> 'Firmado'");
    }

    public function GetReporteByServicioId($ServicioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Nombre as Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, h.HojaVidaId, r.RecibeFecha, 
                r.RecibeHora,r.RecibeNombre, r.RecibeCargo, r.RecibeFirma
                FROM sistemas_reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join sistemas_hojavida as h on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo'
                where r.ServicioId = $ServicioId and r.Estado = 'Firmado';");
    }

    public function GetReporteByServicioId_Sede($SedeId, $ServicioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Nombre as Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, h.HojaVidaId, r.RecibeFecha, 
                r.RecibeHora,r.RecibeNombre, r.RecibeCargo, r.RecibeFirma,
                FROM sistemas_reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join sistemas_hojavida as h on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo'
                where r.ServicioId = $ServicioId and r.SedeId = $SedeId and r.Estado = 'Firmado';");
    }

    public function GetReporteByServicioIdALL($SedeId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Nombre as Equipo, h.Fabricante as Marca, h.Modelo, h.NSerial as Serie, h.SO, h.HojaVidaId, r.RecibeFecha, 
                r.RecibeHora,r.RecibeNombre, r.RecibeCargo, r.RecibeFirma
                FROM sistemas_reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join sistemas_hojavida as h on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo'
                where r.Estado = 'Firmado' and r.SedeId = $SedeId;");
    }

    public function GetReporteByServicioId_year_mes($SedeId, $ServicioId, $Year, $Mes) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Nombre as Equipo, h.Fabricante as Marca, h.Modelo, h.NSerial as Serie, h.SO, h.HojaVidaId, r.RecibeFecha, 
                r.RecibeHora,r.RecibeNombre, r.RecibeCargo, r.RecibeFirma
                FROM sistemas_reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join sistemas_hojavida as h on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo'
                where r.ServicioId = $ServicioId and r.SedeId = $SedeId and r.Estado = 'Firmado' and YEAR(r.FechaReporte) = $Year and Month(r.FechaReporte) = $Mes;");
    }

    public function GetReporteBy_year_mes($SedeId, $Year, $Mes) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, h.HojaVidaId, r.RecibeFecha, 
                r.RecibeHora,r.RecibeNombre, r.RecibeCargo, r.RecibeFirma
                FROM sistemas_reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join sistemas_hojavida as h on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo'
                where r.Estado = 'Firmado' and r.SedeId = $SedeId and YEAR(r.Fecha) = $Year and Month(r.Fecha) = $Mes;");
    }

    public function GetReporteById($ReporteId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, r.Fotos, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable, r.EquipoId, r.Fecha
                , h.Nombre as Equipo, h.Fabricante as Proveedor, h.Modelo, h.NSerial, h.SO, h.HojaVidaId, r.RecibeFecha, ifnull(r.Contador, 'N/A') as Contador, h.TipoArticulo,
                r.TipoServicio, r.Solicitante, r.SolicitudId, u2.NombreCompleto as ResponsableNombre, u2.Cargo as ResponsableCargo, 
                IF(r.ChangeCT = 1, pr.Firma ,u2.Firma) as ResponsableFirma, r.ReporteArchivo, 
                u.NombreCompleto as RecibeNombre, u.Cargo as RecibeCargo, IF(r.ChangeCT = 1, p.Firma, u.Firma) as RecibeFirma,
                r.FallaReportada, r.FallaDetectada,r.RecibeId, 
                r.ProcedimientoRealizado,
                r.Observaciones, r.EstadoFinal,r.Estado,
                TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora
                FROM sistemas_reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join sistemas_hojavida as h on r.EquipoId = h.HojaVidaId
                left join usuario as u on r.RecibeId = u.UsuarioId and r.Estado = 'Firmado' and r.ChangeCT = 0
                left join usuario as u2 on r.ResponsableId = u2.UsuarioId and r.ChangeCT = 0
                left join ct_persona as p on r.RecibeId = p.PersonaId
                left join ct_persona as pr on r.ResponsableId = pr.PersonaId
                where r.ReporteId = $ReporteId and r.EstadoReporte <> 'Inactivo';");
    }

    public function GetReporteBySolicitudId($SolicitudId) {
        return $this->db->objectBuilder()->rawQuery("SELECT ReporteId, ReporteArchivo
                FROM sistemas_reporte
                where SolicitudId = $SolicitudId;");
    }

    public function GetReporteByEquipoId($EquipoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable, r.EquipoId, r.Fecha
                , h.Nombre as Equipo, h.Fabricante as Proveedor, h.Modelo, h.NSerial, h.SO, h.HojaVidaId, r.RecibeFecha,
                r.TipoServicio, r.Solicitante, r.SolicitudId, r.ResponsableNombre, r.ResponsableCargo,r.TipoReporte,  ifnull(r.Contador, 'N/A') as Contador, h.TipoArticulo,
                r.FallaReportada, r.FallaDetectada, 
                r.ProcedimientoRealizado,
                r.Observaciones, r.EstadoFinal, 
                TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora,r.RecibeNombre, r.RecibeCargo, r.RecibeFirma
                FROM sistemas_reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join sistemas_hojavida as h on r.EquipoId = h.HojaVidaId
                where r.EquipoId = $EquipoId;");
    }

    public function GetReporteByEquipoIdByYear($EquipoId, $Year) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, r.Ubicacion, r.Responsable, r.EquipoId, r.Fecha
                FROM sistemas_reporte as r
                where r.EquipoId = $EquipoId and YEAR(r.Fecha) = $Year order by r.ReporteId;");
    }

    public function GetReportesPlantasElectricas($UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.Fecha,r.ReporteId, h.Equipo, h.Marca, h.Modelo,h.Serie,s.Nombre as Servicio, se.Nombre as Sede  
        FROM cronograma as c
        left join HojaVida as h on c.HojaVidaId = h.HojaVidaId
        STRAIGHT_JOIN frecuenciamantenimiento as fm on c.FrecuenciaMantenimientoId = fm.FrecuenciaMantenimientoId
        STRAIGHT_JOIN sistemas_reporte as r on h.HojaVidaId = r.EquipoId
        STRAIGHT_JOIN servicio as s on c.ServicioId = s.ServicioId
        STRAIGHT_JOIN sede as se on c.SedeId = se.SedeId
        STRAIGHT_JOIN usuario as u on $UsuarioId = u.UsuarioId
        STRAIGHT_JOIN serviciousuario as su on u.UsuarioId = su.UsuarioId
        where s.Nombre = 'PLANTAS ELÉCTRICAS' and s.ServicioId = su.ServicioId and r.EstadoReporte <> 'Inactivo'");
    }

    public function FirmarReporte($list, $id) {
        $this->db->where('ReporteId', $id);
        if ($this->db->update('sistemas_reporte', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function GetNReporte() {
        return $this->db->jsonBuilder()->rawQuery("SELECT ReporteId FROM sistemas_reporte order by ReporteId desc limit 1;");
    }

    public function GetAllReportes() {
        return $this->db->jsonBuilder()->rawQuery("SELECT r.ReporteId, r.SolicitudId, s.Nombre as Sede, r.Fecha, 
                ser.Nombre as Servicio, r.TipoServicio, r.FallaDetectada, r.Repuestos, r.TotalRepuesto, h.Serie
                FROM sistemas_reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join sistemas_hojavida as h on r.EquipoId = h.HojaVidaId
                where r.EstadoReporte = 'Activo';");
    }

    public function GetEstadisticas($Year, $Month) {
        return $this->db->objectBuilder()->query("SELECT count(*) as Cantidad, TipoServicio FROM sistemas_reporte where month(Fecha) = $Month and year(Fecha) = $Year and r.EstadoReporte = 'Activo' group by TipoServicio;");
    }

    public function GetReportesBetweenFecha($From, $To) {
        return $this->db->objectBuilder()->query("SELECT ReporteId, Fecha,ModifiedAt,TipoServicio,TipoReporte "
                        . "FROM sistemas_reporte where Estado <> 'Borrador' and Estado <> 'Activo' "
                        . "and (Fecha between '$From' and '$To') "
                        . "and r.EstadoReporte = 'Activo' "
                        . "order by Fecha;");
    }

    public function GetReportesBetweenFechaBySede($From, $To, $SedeId) {
        return $this->db->objectBuilder()->query("SELECT ReporteId, Fecha,ModifiedAt,TipoServicio,TipoReporte, CreatedAt "
                        . "FROM sistemas_reporte where Estado <> 'Inactivo' "
                        . "and (CreatedAt between '$From' and '$To') "
                        . "and SedeId=$SedeId order by Fecha;");
    }

    public function GetReportesBetweenFechaALL($From, $To) {
        return $this->db->objectBuilder()->query("SELECT ReporteId, SedeId, ServicioId, Ubicacion, TipoServicio,EquipoId,FallaDetectada,MedidasAplicadas,TotalRepuesto,EstadoFinal "
                        . "FROM sistemas_reporte where (Fecha between '$From' "
                        . "and '$To')  "
                        . "and r.EstadoReporte = 'Activo' "
                        . "order by Fecha;");
    }

    public function GetReportesBetweenFechaALLBySedeId($From, $To, $SedeId) {
        return $this->db->objectBuilder()->query("SELECT ReporteId, SedeId, ServicioId, Ubicacion, TipoServicio,EquipoId,FallaDetectada,MedidasAplicadas,TotalRepuesto,EstadoFinal "
                        . "FROM sistemas_reporte where (Fecha between '$From' and '$To') "
                        . "and SedeId=$SedeId  "
                        . "and r.EstadoReporte = 'Activo' "
                        . "order by Fecha;");
    }

}
