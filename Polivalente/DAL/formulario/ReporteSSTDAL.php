<?php
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class ReporteSSTDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function CreateReporte($list) {
//        echo print_r($list);
        $ids = $this->db->insertMulti("SST_reporte", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function GetAllReportesByUsuarioServicio($UsuarioId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Nombre, h.Modelo, h.HojaVidaId, 
                r.FechaInspeccion, r.FechaRecarga, r.FechaVencimiento, r.Acceso, r.Demarcacion, r.Demarcacion, r.Senalizacion, r.InstalacionSitioAsignado, r.InstruccionesUso, 
                r.AlturaAdecuada, r.Corneta, r.Manguera, r.CargaExtintor, r.ManijaTransporte, r.ManijaDescarga, r.Pasador, r.SelloSeguridad, r.Observacion, 
                r.CreatedBy, r.CreatedAt, r.Estado
                FROM SST_reporte as r
                inner join usuario as u on $UsuarioId = u.UsuarioId
                inner join sede as s on r.SedeId = s.SedeId
                inner join servicio as ser on r.ServicioId = ser.ServicioId
                inner join serviciousuario as su on u.UsuarioId = su.UsuarioId and su.ServicioId = ser.ServicioId
                left join sst_hojavida as h on r.EquipoId = h.HojaVidaId
                where r.EstadoReporte <> 'Inactivo'
                order by r.ReporteId desc;");
    }
    
    public function GetAllReportesByUsuarioServicio_Autofirmar($UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Nombre, h.Fabricante, h.Modelo, h.NSerial, h.Sector, h.HojaVidaId, r.RecibeFecha, 
                r.RecibeHora,r.RecibeNombre, r.RecibeCargo, r.RecibeFirma, r.TipoServicio, r.Solicitante, r.CreatedBy, r.CreatedAt, r.Estado, u2.Email as RecibeEmail 
                FROM SST_reporte as r
                inner join usuario as u on $UsuarioId = u.UsuarioId
                inner join usuario as u2 on r.RecibeNombre = u2.NombreCompleto
                inner join sede as s on r.SedeId = s.SedeId
                inner join servicio as ser on r.ServicioId = ser.ServicioId
                inner join serviciousuario as su on u.UsuarioId = su.UsuarioId and su.ServicioId = ser.ServicioId
                left join sst_hojavida as h on r.EquipoId = h.HojaVidaId  
                and r.Estado = 'Borrador'
                order by r.ReporteId desc;");
    }
    
    public function GetAllReportesByUsuarioServicio_AutofirmarEmail($Email) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, 
			ser.Nombre as Servicio, r.Solicitante, r.Ubicacion, r.Responsable, 
			r.TipoServicio, h.Nombre, h.Fabricante, h.Modelo, h.NSerial, 
			r.FallaReportada, r.FallaDetectada, h.ClaseExtintor, h.NumeroExtintor, 
			r.ProcedimientoRealizado,
			r.Observaciones, r.EstadoFinal,
			r.RecibeCargo, r.RecibeFecha,r.RecibeFirma, u.Email as RecibeEmail, TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora, r.RecibeNombre,
			r.ResponsableCargo, r.ResponsableFirma, r.ResponsableNombre, r.ReporteArchivo, r.SedeId, r.ServicioId, h.HojaVidaId as EquipoId
			FROM sst_reporte as r
			inner join sede as s on r.SedeId = s.SedeId
			inner join servicio as ser on r.ServicioId = ser.ServicioId
			inner join sst_hojavida as h on r.EquipoId = h.HojaVidaId
			inner join usuario as u on r.RecibeNombre = u.NombreCompleto
			where u.Email = '$Email' and  r.EstadoReporte <> 'Inactivo' and r.Estado <> 'Firmado'");
    }
    public function GetReporteByEmail($Email) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede,
			ser.Nombre as Servicio, r.Solicitante, r.Ubicacion, r.Responsable, 
			r.TipoServicio, h.Nombre, h.Fabricante, h.Modelo, h.NSerial, 
			r.FallaReportada, r.FallaDetectada, h.ClaseExtintor, h.NumeroExtintor, 
			r.ProcedimientoRealizado,
			r.Observaciones, r.EstadoFinal,
			r.RecibeCargo, r.RecibeFecha,r.RecibeFirma, u.Email as RecibeEmail, TIME_FORMAT(r.RecibeHora, '%h:%i %p') as RecibeHora, r.RecibeNombre,
			r.ResponsableCargo, r.ResponsableFirma, r.ResponsableNombre, r.ReporteArchivo, r.SedeId, r.ServicioId, h.HojaVidaId as EquipoId
			FROM sst_reporte as r
			inner join sede as s on r.SedeId = s.SedeId
			inner join servicio as ser on r.ServicioId = ser.ServicioId
			inner join sst_hojavida as h on r.EquipoId = h.HojaVidaId
			inner join usuario as u on r.RecibeNombre = u.NombreCompleto
			where u.Email = '$Email' and  r.EstadoReporte <> 'Inactivo' and r.Estado <> 'Firmado';");
    }
    
    public function GetReporteByServicioId($ServicioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Equipo, h.Marca, h.Modelo, h.Serie, h.HojaVidaId,
                r.FechaInspeccion, r.FechaRecarga, r.FechaVencimiento, r.Acceso, r.Demarcacion, r.Demarcacion, r.Senalizacion, r.InstalacionSitioAsignado, r.InstruccionesUso, 
                r.AlturaAdecuada, r.Corneta, r.Manguera, r.CargaExtintor, r.ManijaTransporte, r.ManijaDescarga, r.Pasador, r.SelloSeguridad, r.Observacion 
                FROM SST_reporte as r
                inner join sede as s on r.SedeId = s.SedeId
                inner join servicio as ser on r.ServicioId = ser.ServicioId
                left join sst_hojavida as h on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo'
                where r.ServicioId = $ServicioId and r.EstadoReporte = 'Firmado';");
    }
    
    public function GetReporteByServicioId_Sede($SedeId, $ServicioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Equipo, h.Marca, h.Modelo, h.HojaVidaId,
                r.FechaInspeccion, r.FechaRecarga, r.FechaVencimiento, r.Acceso, r.Demarcacion, r.Demarcacion, r.Senalizacion, r.InstalacionSitioAsignado, r.InstruccionesUso, 
                r.AlturaAdecuada, r.Corneta, r.Manguera, r.CargaExtintor, r.ManijaTransporte, r.ManijaDescarga, r.Pasador, r.SelloSeguridad, r.Observacion
                FROM SST_reporte as r
                inner join sede as s on r.SedeId = s.SedeId
                inner join servicio as ser on r.ServicioId = ser.ServicioId
                left join sst_hojavida as h on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo'
                where r.ServicioId = $ServicioId and r.SedeId = $SedeId and r.Estado = 'Firmado';");
    }
    
    public function GetReporteByServicioIdALL($SedeId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Nombre as Equipo, h.Fabricante, h.Modelo, h.NSerial as Serie, h.Sector, h.HojaVidaId,
                r.FechaInspeccion, r.FechaRecarga, r.FechaVencimiento, r.Acceso, r.Demarcacion, r.Demarcacion, r.Senalizacion, r.InstalacionSitioAsignado, r.InstruccionesUso, 
                r.AlturaAdecuada, r.Corneta, r.Manguera, r.CargaExtintor, r.ManijaTransporte, r.ManijaDescarga, r.Pasador, r.SelloSeguridad, r.Observacion
                FROM SST_reporte as r
                inner join sede as s on r.SedeId = s.SedeId
                inner join servicio as ser on r.ServicioId = ser.ServicioId
                left join sst_hojavida as h on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo'
                where r.Estado = 'Firmado' and r.SedeId = $SedeId;");
    }
    
    public function GetReporteByServicioId_year_mes($SedeId, $ServicioId, $Year, $Mes) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Nombre as Equipo, h.Fabricante as Marca, h.Modelo, h.NSerial as Serie, h.Sector, h.HojaVidaId, r.RecibeFecha, 
                r.RecibeHora,r.RecibeNombre, r.RecibeCargo, r.RecibeFirma
                FROM SST_reporte as r
                inner join sede as s on r.SedeId = s.SedeId
                inner join servicio as ser on r.ServicioId = ser.ServicioId
                left join sst_hojavida as h on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo'
                where r.ServicioId = $ServicioId and r.SedeId = $SedeId and r.Estado = 'Firmado' and YEAR(r.FechaReporte) = $Year and Month(r.FechaReporte) = $Mes;");
    }
    
    public function GetReporteBy_year_mes($SedeId, $Year, $Mes) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable
                , h.Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, h.HojaVidaId, r.RecibeFecha, 
                r.RecibeHora,r.RecibeNombre, r.RecibeCargo, r.RecibeFirma
                FROM SST_reporte as r
                inner join sede as s on r.SedeId = s.SedeId
                inner join servicio as ser on r.ServicioId = ser.ServicioId
                left join sst_hojavida as h on r.EquipoId = h.HojaVidaId and h.Estado = 'Activo'
                where r.Estado = 'Firmado' and r.SedeId = $SedeId and YEAR(r.Fecha) = $Year and Month(r.Fecha) = $Mes;");
    }
    
    public function GetReporteById($ReporteId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable, r.EquipoId
                , h.Nombre as Equipo, h.Fabricante as Proveedor, h.Modelo, h.NSerial, h.Sector, h.HojaVidaId,
                r.FechaInspeccion, r.FechaRecarga, r.FechaVencimiento, r.Acceso, r.Demarcacion, r.Demarcacion, r.Senalizacion, r.InstalacionSitioAsignado, r.InstruccionesUso, 
                r.AlturaAdecuada, r.Corneta, r.Manguera, r.CargaExtintor, r.ManijaTransporte, r.ManijaDescarga, r.Pasador, r.SelloSeguridad, r.Observacion
                FROM SST_reporte as r
                inner join sede as s on r.SedeId = s.SedeId
                inner join servicio as ser on r.ServicioId = ser.ServicioId
                left join sst_hojavida as h on r.EquipoId = h.HojaVidaId
                where r.ReporteId = $ReporteId and r.EstadoReporte <> 'Inactivo';");
    }
    
    public function GetReporteBySolicitudId($SolicitudId) {
        return $this->db->objectBuilder()->rawQuery("SELECT ReporteId, ReporteArchivo
                FROM SST_reporte
                where SolicitudId = $SolicitudId;");
    }
    
    public function GetReporteByEquipoId($EquipoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, LPAD(r.ReporteId,4,'0') as NumeroReporte, s.Nombre as Sede, s.SedeId, 
                ser.Nombre as Servicio, ser.ServicioId, r.Ubicacion, r.Responsable, r.EquipoId
                , h.Nombre as Equipo, h.Fabricante as Proveedor, h.Modelo, h.NSerial, h.Sector, h.HojaVidaId, h.ClaseExtintor, h.NumeroExtintor, h.FechaInstalacion, 
                r.FechaInspeccion, r.FechaRecarga, r.FechaVencimiento, r.Acceso, r.Demarcacion, r.Demarcacion, r.Senalizacion, r.InstalacionSitioAsignado, r.InstruccionesUso, 
                r.AlturaAdecuada, r.Corneta, r.Manguera, r.CargaExtintor, r.ManijaTransporte, r.ManijaDescarga, r.Pasador, r.SelloSeguridad, r.Observacion               
                FROM SST_reporte as r
                inner join sede as s on r.SedeId = s.SedeId
                inner join servicio as ser on r.ServicioId = ser.ServicioId
                left join sst_hojavida as h on r.EquipoId = h.HojaVidaId
                where r.EquipoId = $EquipoId and r.EstadoReporte <> 'Inactivo';");
    }
    
    
    public function FirmarReporte($list, $id) {
        $this->db->where('ReporteId', $id);
        if ($this->db->update('SST_reporte', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function GetNReporte() {
        return $this->db->jsonBuilder()->rawQuery("SELECT ReporteId FROM SST_reporte order by ReporteId desc limit 1;");
    }
    
    public function GetAllReportes() {
        return $this->db->jsonBuilder()->rawQuery("SELECT r.ReporteId, s.Nombre as Sede, 
                ser.Nombre as Servicio, r.TipoServicio, r.FallaDetectada, h.ClaseExtintor, h.NumeroExtintor, r.Repuestos, r.TotalRepuesto, h.Serie
                FROM SST_reporte as r
                inner join sede as s on r.SedeId = s.SedeId
                inner join servicio as ser on r.ServicioId = ser.ServicioId
                left join sst_hojavida as h on r.EquipoId = h.HojaVidaId
                where r.EstadoReporte = 'Activo';");
    }
    
    public function GetEstadisticas($Year,$Month) {
        return $this->db->objectBuilder()->query("SELECT count(*) as Cantidad, TipoServicio FROM SST_reporte where month(Fecha) = $Month and year(Fecha) = $Year and r.EstadoReporte = 'Activo' group by TipoServicio;");
    }
    
    public function GetReportesBetweenFecha($From,$To) {
        return $this->db->objectBuilder()->query("SELECT ReporteId, Fecha,ModifiedAt,TipoServicio,TipoReporte "
                . "FROM SST_reporte where Estado <> 'Borrador' and Estado <> 'Activo' "
                . "and (Fecha between '$From' and '$To') "
                . "and r.EstadoReporte = 'Activo' "
                . "order by Fecha;");
    }
    public function GetReportesBetweenFechaBySede($From,$To,$SedeId) {
        return $this->db->objectBuilder()->query("SELECT ReporteId, Fecha,ModifiedAt,TipoServicio,TipoReporte, CreatedAt "
                . "FROM SST_reporte where Estado <> 'Inactivo' "
                . "and (CreatedAt between '$From' and '$To') "
                . "and SedeId=$SedeId order by Fecha;");
    }
    public function GetReportesBetweenFechaALL($From,$To) {
        return $this->db->objectBuilder()->query("SELECT ReporteId, SedeId, ServicioId, Ubicacion, TipoServicio,EquipoId,FallaDetectada,MedidasAplicadas,TotalRepuesto,EstadoFinal "
                . "FROM SST_reporte where (Fecha between '$From' "
                . "and '$To')  "
                . "and r.EstadoReporte = 'Activo' "
                . "order by Fecha;");
    }
    public function GetReportesBetweenFechaALLBySedeId($From,$To,$SedeId) {
        return $this->db->objectBuilder()->query("SELECT ReporteId, SedeId, ServicioId, Ubicacion, TipoServicio,EquipoId,FallaDetectada,MedidasAplicadas,TotalRepuesto,EstadoFinal "
                . "FROM SST_reporte where (Fecha between '$From' and '$To') "
                . "and SedeId=$SedeId  "
                . "and r.EstadoReporte = 'Activo' "
                . "order by Fecha;");
    }
}
