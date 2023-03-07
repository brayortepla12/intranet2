<?php
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";
class ReporteDAL
{
    private $db;

    public function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
        $this->db->disconnect();
    }

    public function GetReporteByRecibeId($RecibeId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, r.SolicitudId, LPAD(r.ReporteId,5,'0') as NumeroReporte, s.Nombre as Sede, r.Fecha, 
                ser.Nombre as Servicio, r.Solicitante, r.Ubicacion, r.Responsable, 
                r.TipoServicio, h.Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, 
                r.FallaReportada, r.FallaDetectada, 
                r.ProcedimientoRealizado, r.MedidasAplicadas, r.Repuestos, 
                r.Observaciones, r.EstadoFinal,
                r.RecibeCargo, r.RecibeFecha,r.RecibeFirma, TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora, r.RecibeNombre,
                r.ResponsableCargo, r.ResponsableFirma, r.ResponsableNombre, r.TotalRepuesto, r.ReporteArchivo, r.SedeId, r.ServicioId, h.HojaVidaId as EquipoId
                FROM reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join hojavida as h on r.EquipoId = h.HojaVidaId
                where r.RecibeId = '$RecibeId' and  r.EstadoReporte <> 'Inactivo' and  r.EstadoReporte <> 'Inactivo' and r.Estado <> 'Firmado';");
    }

    public function GetAllReportesByUsuarioServicio_AutofirmarEmail($Email)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, r.SolicitudId, LPAD(r.ReporteId,5,'0') as NumeroReporte, s.Nombre as Sede, r.Fecha, 
                ser.Nombre as Servicio, r.Solicitante, r.Ubicacion, r.Responsable, 
                r.TipoServicio, h.Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, 
                r.FallaReportada, r.FallaDetectada, 
                r.ProcedimientoRealizado, r.MedidasAplicadas, r.Repuestos, 
                r.Observaciones, r.EstadoFinal,
                r.RecibeCargo, r.RecibeFecha,u.Firma as RecibeFirma, u.Email as RecibeEmail, TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora, r.RecibeNombre,
                r.ResponsableCargo, u2.Firma as ResponsableFirma, r.ResponsableNombre, r.TotalRepuesto, r.ReporteArchivo, r.SedeId, r.ServicioId, h.HojaVidaId as EquipoId, r.Estado
                FROM reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join hojavida as h on r.EquipoId = h.HojaVidaId
                left join usuario as u on r.RecibeId = u.UsuarioId and r.Estado = 'Borrador'
                STRAIGHT_JOIN usuario as u2 on r.ResponsableId = u2.UsuarioId
                where u.Email = '$Email' and  r.EstadoReporte <> 'Inactivo'");
    }

    public function CreateReporte($list)
    {
        //        echo print_r($list);
        $ids = $this->db->insertMulti("reporte", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }


    public function CreateEventoSolicitud($list)
    {
        $ids = $this->db->insertMulti("pol_eventosolicitud", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }



    public function GetAllReportesByUsuarioServicio($UsuarioId, $Dia, $Mes, $Year)
    {
        return $this->db->jsonBuilder()->rawQuery("SELECT r.ReporteId, r.SolicitudId, s.Nombre as Sede,
                ser.Nombre as Servicio, r.TipoServicio, r.TotalRepuesto, h.Equipo, h.HojaVidaId, r.Ubicacion, r.CreatedAt, r.CreatedBy,r.Solicitante, r.Estado, h.Serie
                FROM reporte as r
                STRAIGHT_JOIN usuario as u on $UsuarioId = u.UsuarioId
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                STRAIGHT_JOIN serviciousuario as su on u.UsuarioId = su.UsuarioId and su.ServicioId = ser.ServicioId
                left join hojavida as h on r.EquipoId = h.HojaVidaId
                where r.EstadoReporte = 'Activo' and YEAR(r.Fecha) = $Year AND ((month(r.Fecha) = '$Mes' and (day(r.Fecha) = '$Dia' or '$Dia' = 'TODOS')) or '$Mes' = 'TODOS')
                order by r.ReporteId desc;");
    }

    public function GetAllReportesByUsuarioServicio_Autofirmar($UsuarioId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, r.SolicitudId, s.Nombre as Sede,
                ser.Nombre as Servicio, r.TipoServicio, r.TotalRepuesto, h.Equipo, h.HojaVidaId, r.Ubicacion, r.CreatedAt, r.CreatedBy,r.Solicitante, r.Estado,
                r.RecibeNombre, r.RecibeFirma, u2.Email as RecibeEmail, h.Serie
                FROM reporte as r
                STRAIGHT_JOIN usuario as u on $UsuarioId = u.UsuarioId
                STRAIGHT_JOIN usuario as u2 on r.RecibeNombre = u2.NombreCompleto
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                STRAIGHT_JOIN serviciousuario as su on u.UsuarioId = su.UsuarioId and su.ServicioId = ser.ServicioId
                left join hojavida as h on r.EquipoId = h.HojaVidaId  
                and r.Estado = 'Borrador' and r.EstadoReporte = 'Activo' 
                order by r.ReporteId desc;");
    }

    public function GetReporteByServicioId($ServicioId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, r.SolicitudId, LPAD(r.ReporteId,5,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, r.Fecha, 
                ser.Nombre as Servicio, ser.ServicioId, r.Solicitante, r.Ubicacion, r.Responsable, 
                r.TipoServicio, h.Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, h.HojaVidaId,
                r.FallaReportada, r.FallaDetectada, 
                r.ProcedimientoRealizado, r.MedidasAplicadas, r.Repuestos, 
                r.Observaciones, r.EstadoFinal,
                r.RecibeCargo, r.RecibeFecha,u.Firma as RecibeFirma, TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora, r.RecibeNombre,
                r.ResponsableCargo, u2.Firma as ResponsableFirma, r.ResponsableNombre, r.TotalRepuesto, r.ReporteArchivo, r.Ciudad, r.HoraInicio, r.HoraFinal, r.NivelCombustible,
                r.NivelAguaRefrigerante, r.NivelAceite, r.NivelElectrolitoBateria, r.VoltajeBateria, r.FechaUltCambioAceite, r.FiltroAire, r.Fugas
                FROM reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join usuario as u on r.RecibeId = u.UsuarioId and r.Estado = 'Firmado'
                STRAIGHT_JOIN usuario as u2 on r.ResponsableId = u2.UsuarioId
                left join hojavida as h on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo'
                where r.ServicioId = $ServicioId and r.Estado = 'Firmado';");
    }

    public function GetReporteByServicioId_Sede($SedeId, $ServicioId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, r.SolicitudId, LPAD(r.ReporteId,5,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, r.Fecha, 
                ser.Nombre as Servicio, ser.ServicioId, r.Solicitante, r.Ubicacion, r.Responsable, 
                r.TipoServicio, h.Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, h.HojaVidaId,
                r.FallaReportada, r.FallaDetectada, 
                r.ProcedimientoRealizado, r.MedidasAplicadas, r.Repuestos, 
                r.Observaciones, r.EstadoFinal,
                r.RecibeCargo, r.RecibeFecha,u.Firma as RecibeFirma, TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora, r.RecibeNombre,
                r.ResponsableCargo, u2.Firma as ResponsableFirma, r.ResponsableNombre, r.TotalRepuesto, r.ReporteArchivo, r.Ciudad, r.HoraInicio, r.HoraFinal, r.NivelCombustible,
                r.NivelAguaRefrigerante, r.NivelAceite, r.NivelElectrolitoBateria, r.VoltajeBateria, r.FechaUltCambioAceite, r.FiltroAire, r.Fugas
                FROM hojavida as h
                STRAIGHT_JOIN reporte as r on h.Estado = 'Activo' and GetLastReporteidByHojaVidaId(h.HojaVidaId) = r.ReporteId
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join usuario as u on r.RecibeId = u.UsuarioId
                STRAIGHT_JOIN usuario as u2 on r.ResponsableId = u2.UsuarioId
                where r.ServicioId = $ServicioId and r.SedeId = $SedeId;");
    }

    public function GetReporteByServicioIdALL($SedeId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, r.SolicitudId, LPAD(r.ReporteId,5,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, r.Fecha, 
                ser.Nombre as Servicio, ser.ServicioId, r.Solicitante, r.Ubicacion, r.Responsable, 
                r.TipoServicio, h.Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, h.HojaVidaId,
                r.FallaReportada, r.FallaDetectada, 
                r.ProcedimientoRealizado, r.MedidasAplicadas, r.Repuestos, 
                r.Observaciones, r.EstadoFinal,
                r.RecibeCargo, r.RecibeFecha,u.Firma as RecibeFirma, TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora, r.RecibeNombre,
                r.ResponsableCargo, u2.Firma as ResponsableFirma, r.ResponsableNombre, r.TotalRepuesto, r.ReporteArchivo, r.Ciudad, r.HoraInicio, r.HoraFinal, r.NivelCombustible,
                r.NivelAguaRefrigerante, r.NivelAceite, r.NivelElectrolitoBateria, r.VoltajeBateria, r.FechaUltCambioAceite, r.FiltroAire, r.Fugas
                FROM hojavida as h
                STRAIGHT_JOIN reporte as r on h.Estado = 'Activo' and GetLastReporteidByHojaVidaId(h.HojaVidaId) = r.ReporteId
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join usuario as u on r.RecibeId = u.UsuarioId
                STRAIGHT_JOIN usuario as u2 on r.ResponsableId = u2.UsuarioId
                where r.Estado = 'Firmado' and r.SedeId = $SedeId;");
    }

    public function GetReporteByServicioId_year_mes($SedeId, $ServicioId, $Year, $Mes)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, r.SolicitudId, LPAD(r.ReporteId,5,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, r.Fecha, 
                ser.Nombre as Servicio, ser.ServicioId, r.Solicitante, r.Ubicacion, r.Responsable, 
                r.TipoServicio, h.Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, h.HojaVidaId,
                r.FallaReportada, r.FallaDetectada, 
                r.ProcedimientoRealizado, r.MedidasAplicadas, r.Repuestos, 
                r.Observaciones, r.EstadoFinal,
                r.RecibeCargo, r.RecibeFecha,r.RecibeFirma, TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora, r.RecibeNombre,
                r.ResponsableCargo, r.ResponsableFirma, r.ResponsableNombre, r.TotalRepuesto, r.ReporteArchivo, r.Ciudad, r.HoraInicio, r.HoraFinal, r.NivelCombustible,
                r.NivelAguaRefrigerante, r.NivelAceite, r.NivelElectrolitoBateria, r.VoltajeBateria, r.FechaUltCambioAceite, r.FiltroAire, r.Fugas
                FROM hojavida as h
                STRAIGHT_JOIN reporte as r on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo' and GetLastReporteidByHojaVidaId(h.HojaVidaId) = r.ReporteId
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                where r.ServicioId = $ServicioId and r.SedeId = $SedeId and YEAR(r.Fecha) = $Year and Month(r.Fecha) = $Mes;");
    }

    public function GetReporteBy_year_mes($SedeId, $Year, $Mes)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, r.SolicitudId, LPAD(r.ReporteId,5,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, r.Fecha, 
                ser.Nombre as Servicio, ser.ServicioId, r.Solicitante, r.Ubicacion, r.Responsable, 
                r.TipoServicio, h.Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, h.HojaVidaId,
                r.FallaReportada, r.FallaDetectada, 
                r.ProcedimientoRealizado, r.MedidasAplicadas, r.Repuestos, 
                r.Observaciones, r.EstadoFinal,
                r.RecibeCargo, r.RecibeFecha,r.RecibeFirma, TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora, r.RecibeNombre,
                r.ResponsableCargo, r.ResponsableFirma, r.ResponsableNombre, r.TotalRepuesto, r.ReporteArchivo, r.Ciudad, r.HoraInicio, r.HoraFinal, r.NivelCombustible,
                r.NivelAguaRefrigerante, r.NivelAceite, r.NivelElectrolitoBateria, r.VoltajeBateria, r.FechaUltCambioAceite, r.FiltroAire, r.Fugas
                FROM hojavida as h
                STRAIGHT_JOIN reporte as r on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo' and GetLastReporteidByHojaVidaId(h.HojaVidaId) = r.ReporteId
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                where r.Estado = 'Firmado' and r.SedeId = $SedeId and YEAR(r.Fecha) = $Year and Month(r.Fecha) = $Mes;");
    }

    public function GetReporteById($ReporteId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, r.SolicitudId, LPAD(r.ReporteId,5,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, r.Fecha, 
                ser.Nombre as Servicio,ser.ServicioId, r.Solicitante, r.Ubicacion, r.Responsable, 
                r.TipoServicio, h.Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario,r.EquipoId, 
                r.FallaReportada, r.FallaDetectada, 
                r.ProcedimientoRealizado, r.MedidasAplicadas, r.Repuestos, 
                r.Observaciones, r.EstadoFinal,
                r.CreatedBy, p.UsuarioIntranetId,
                r.RecibeCargo, r.RecibeFecha, IF(r.ChangeCT = 1, p.Firma, u.Firma) as RecibeFirma, TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora, r.RecibeNombre, 
                r.ResponsableCargo, IF(r.ChangeCT = 1, pr.Firma ,u2.Firma) as ResponsableFirma, 
                r.ResponsableNombre, r.TotalRepuesto, 
                r.ReporteArchivo, r.Ciudad, r.HoraInicio, r.HoraFinal, r.NivelCombustible,
                r.NivelAguaRefrigerante, r.NivelAceite, r.NivelElectrolitoBateria, r.VoltajeBateria, r.FechaUltCambioAceite, r.FiltroAire, r.Fugas, r.RecibeId, p.UsuarioIntranetId as RecibeUsuarioIntranetId, r.Estado
                FROM reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join usuario as u on r.RecibeId = u.UsuarioId and r.Estado = 'Firmado' and r.ChangeCT = 0
                left join usuario as u2 on r.ResponsableId = u2.UsuarioId and r.ChangeCT = 0
                left join ct_persona as p on r.RecibeId = p.PersonaId
                left join ct_persona as pr on r.ResponsableId = pr.PersonaId
                left join hojavida as h on r.EquipoId = h.HojaVidaId
                where r.ReporteId = $ReporteId and r.EstadoReporte = 'Activo';");
    }

    public function GetReporteBySolicitudId($SolicitudId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT ReporteId, ReporteArchivo
                FROM reporte
                where SolicitudId = $SolicitudId;");
    }

    public function GetReporteByEquipoId($EquipoId)
    {
        $sql = "SELECT r.ReporteId, r.ReporteArchivo, r.TipoReporte, r.SolicitudId, LPAD(r.ReporteId,5,'0') as NumeroReporte, s.Nombre as Sede, r.Fecha, 
        ser.Nombre as Servicio, r.Solicitante, r.Ubicacion, r.Responsable, 
        r.TipoServicio, h.Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, 
        r.FallaReportada, r.FallaDetectada, 
        r.ProcedimientoRealizado, r.MedidasAplicadas, r.Repuestos, 
        r.Observaciones, r.EstadoFinal,
        r.RecibeCargo, r.Recibefecha,u.Firma as RecibeFirma, TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora, r.RecibeNombre,
        r.ResponsableCargo, u2.Firma as ResponsableFirma, r.ResponsableNombre, r.TotalRepuesto
        FROM reporte as r
        STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
        STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
        left join hojavida as h on r.EquipoId = h.HojaVidaId
        left join usuario as u on r.RecibeId = u.UsuarioId and r.Estado = 'Firmado'
        left join ct_persona as p on r.ResponsableId = p.PersonaId 
        left join usuario as u2 on p.UsuarioIntranetId = u2.UsuarioId
        where r.EquipoId = $EquipoId and r.EstadoReporte = 'Activo';";
        return $this->db->objectBuilder()->rawQuery($sql);
    }

    public function GetReportesPlantasElectricas($UsuarioId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT r.Fecha,r.ReporteId, h.Equipo, h.Marca, h.Modelo,h.Serie,s.Nombre as Servicio, se.Nombre as Sede  
        FROM cronograma as c
        left join HojaVida as h on c.HojaVidaId = h.HojaVidaId
        STRAIGHT_JOIN frecuenciamantenimiento as fm on c.FrecuenciaMantenimientoId = fm.FrecuenciaMantenimientoId
        STRAIGHT_JOIN reporte as r on h.HojaVidaId = r.EquipoId
        STRAIGHT_JOIN servicio as s on c.ServicioId = s.ServicioId
        STRAIGHT_JOIN sede as se on c.SedeId = se.SedeId
        STRAIGHT_JOIN usuario as u on $UsuarioId = u.UsuarioId
        STRAIGHT_JOIN serviciousuario as su on u.UsuarioId = su.UsuarioId
        where s.Nombre = 'PLANTAS ELÉCTRICAS' and s.ServicioId = su.ServicioId 
        and r.EstadoReporte = 'Activo';");
    }

    public function FirmarReporteALL($RecibeId)
    {
        $this->db->query("UPDATE reporte as r SET r.Estado = 'Firmado' where r.RecibeId = $RecibeId and r.Estado = 'Borrador';");
    }

    public function FirmarReporte($list, $id)
    {
        $this->db->where('ReporteId', $id);
        if ($this->db->update('Reporte', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function GetNReporte()
    {
        return $this->db->jsonBuilder()->rawQuery("SELECT ReporteId FROM reporte order by ReporteId desc limit 1;");
    }

    public function GetAllReportes()
    {
        return $this->db->jsonBuilder()->rawQuery("SELECT r.ReporteId, r.SolicitudId, s.Nombre as Sede, r.Fecha, 
                ser.Nombre as Servicio, r.TipoServicio, r.FallaDetectada, r.Repuestos, r.TotalRepuesto, h.Serie
                FROM reporte as r
                STRAIGHT_JOIN sede as s on r.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on r.ServicioId = ser.ServicioId
                left join hojavida as h on r.EquipoId = h.HojaVidaId
                where r.EstadoReporte = 'Activo';");
    }

    public function GetEstadisticas($Year, $Month)
    {
        return $this->db->objectBuilder()->query("SELECT count(*) as Cantidad, TipoServicio FROM reporte where month(Fecha) = $Month and year(Fecha) = $Year and r.EstadoReporte = 'Activo' group by TipoServicio;");
    }

    public function GetReportesBetweenFecha($From, $To)
    {
        return $this->db->objectBuilder()->query("SELECT ReporteId, Fecha,ModifiedAt,TipoServicio,TipoReporte "
            . "FROM reporte where Estado <> 'Borrador' and Estado <> 'Activo' "
            . "and (Fecha between '$From' and '$To') "
            . "and r.EstadoReporte = 'Activo' "
            . "order by Fecha;");
    }
    public function GetReportesBetweenFechaBySede($From, $To, $SedeId)
    {
        return $this->db->objectBuilder()->query("SELECT ReporteId, Fecha,ModifiedAt,TipoServicio,TipoReporte, CreatedAt "
            . "FROM reporte where Estado <> 'Activo' "
            . "and (CreatedAt between '$From' and '$To') "
            . "and SedeId=$SedeId order by Fecha;");
    }
    public function GetReportesBetweenFechaALL($From, $To)
    {
        return $this->db->objectBuilder()->query("SELECT ReporteId, SedeId, ServicioId, Ubicacion, TipoServicio,EquipoId,FallaDetectada,MedidasAplicadas,TotalRepuesto,EstadoFinal "
            . "FROM reporte where (Fecha between '$From' "
            . "and '$To')  "
            . "and r.EstadoReporte = 'Activo' "
            . "order by Fecha;");
    }
    public function GetReportesBetweenFechaALLBySedeId($From, $To, $SedeId)
    {
        return $this->db->objectBuilder()->query("SELECT ReporteId, SedeId, ServicioId, Ubicacion, TipoServicio,EquipoId,FallaDetectada,MedidasAplicadas,TotalRepuesto,EstadoFinal "
            . "FROM reporte where (Fecha between '$From' and '$To') "
            . "and SedeId=$SedeId  "
            . "and EstadoReporte = 'Activo' "
            . "order by Fecha;");
    }
}
