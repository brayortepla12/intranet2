<?php
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";
class SolicitudDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
       $dotenv->safeLoad();
       $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
       $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function getUsuarioSolicitudPol($SolicitudId, $prefijo) {
        return $this->db->objectBuilder()->rawQuery("SELECT u.NombreCompleto, u.Email FROM polivalente.usuario as u 
        inner join {$prefijo}_solicitud as s on u.UsuarioId = s.UsuarioSolicitaId
        where SolicitudId = $SolicitudId;");
    }
    
    public function CreateSolicitudPol($list, $prefijo) {
        $ids = $this->db->insertMulti("{$prefijo}_solicitud", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateDetalleSolicitudPol($list, $prefijo) {
        $ids = $this->db->insertMulti("{$prefijo}_detallesolicitud", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateEventoSolicitudPol($list, $Prefijo) {
        $ids = $this->db->insertMulti("{$Prefijo}_eventosolicitud", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function UpdateSolicitudPol($list, $id, $Prefijo) {
        $this->db->where ('SolicitudId', $id);
        if ($this->db->update("{$Prefijo}_solicitud", $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function GetSolIfExistByPedido2($PedidoAlmacenId) {
        return $this->db->objectBuilder()->rawQuery("select 'pol' as Tipo, esp.SolicitudId from pol_eventosolicitud as esp where esp.Pedido2_0Id = $PedidoAlmacenId
        union 
        select 'sistemas' as Tipo, ssp.SolicitudId from sistemas_eventosolicitud as ssp where ssp.Pedido2_0Id = $PedidoAlmacenId
        union 
        select 'biomedicos' as Tipo, bsp.SolicitudId from biomedicos_eventosolicitud as bsp where bsp.Pedido2_0Id = $PedidoAlmacenId");
    }
    public function GetSolIfExistByProceso($ProcesoId) {
        return $this->db->objectBuilder()->rawQuery("select 'pol' as Tipo, esp.SolicitudId from pol_eventosolicitud as esp where esp.ProcesoId = $ProcesoId
        union 
        select 'sistemas' as Tipo, ssp.SolicitudId from sistemas_eventosolicitud as ssp where ssp.ProcesoId = $ProcesoId
        union 
        select 'biomedicos' as Tipo, bsp.SolicitudId from biomedicos_eventosolicitud as bsp where bsp.ProcesoId = $ProcesoId");
    }
    
    public function GetTotalSolicitudes() {
        return $this->db->objectBuilder()->rawQuery("select 
        (select count(sp.SolicitudId) from pol_solicitud as sp where sp.IsFinalizada = 0) as TotalPolivalente,
        (select count(sp.SolicitudId) from sistemas_solicitud as sp where sp.IsFinalizada = 0) as TotalSistemas,
        (select count(sp.SolicitudId) from biomedicos_solicitud as sp where sp.IsFinalizada = 0) as TotalBiomedico");
    }
    
    public function GetReporteExternoById($ReporteExternoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT ReporteArchivo FROM polivalente.reporte where ReporteId = $ReporteExternoId;");
    }
    
    public function GetReporteExternoBiomedicoById($ReporteExternoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT ReporteArchivo FROM biomedico.reporte where ReporteId = $ReporteExternoId;");
    }
    
    public function GetEventosBySolicitudId($SolicitudId, $Prefijo) {
        return $this->db->objectBuilder()->rawQuery("SELECT EventoSolicitudId, FechaEvento,NombreBreveEvento, NombreUsuario
        , Pedido2_0Id , PedidoId , PedidoFarmaciaId , ReporteId , ReporteExternoId , ProcesoId, TecnicoResponsable , Descripcion
        FROM {$Prefijo}_eventosolicitud where SolicitudId = $SolicitudId order by EventoSolicitudId DESC;");
    }

    public function GetReportesBySolicitudId($SolicitudId, $Prefijo, $tablas) {
        return $this->db->objectBuilder()->rawQuery("SELECT es.ProcesoId, es.EventoSolicitudId, r.ReporteId, r.TipoServicio, r.Ubicacion, 
        r.EquipoId, h.{$tablas->Equipo} as Equipo, 
        r.Solicitante, r.Responsable, r.CreatedAt FROM {$tablas->reporte} as r 
        INNER JOIN {$Prefijo}_eventosolicitud as es on r.ReporteId = es.ReporteId
        INNER JOIN $tablas->hojavida as h on h.HojaVidaId = r.EquipoId
        WHERE es.SolicitudId = $SolicitudId ORDER BY r.ReporteId DESC;");
    }

    public function GetProcesosBySolicitudId($SolicitudId, $Prefijo, $tablas) {
        return $this->db->objectBuilder()->rawQuery("SELECT 
            p.ProcesoId,
            p.Nombre,
            p.OrdenEnCurso,
            pro.Nombre as Protocolo,
            PC_GetNombreVerificadorActual(p.ProtocoloId, p.OrdenEnCurso) as PerEnTurno,
            p.Estado,
            p.CreatedAt
        FROM
            pc_proceso AS p
                INNER JOIN
            {$Prefijo}_eventosolicitud AS es ON p.ProcesoId = es.ProcesoId
                INNER JOIN
            pc_protocolo AS pro ON p.ProtocoloId = pro.ProtocoloId
        WHERE
            es.SolicitudId = $SolicitudId
        ORDER BY p.ProcesoId DESC;");
    }
    
    public function GetSolicitudesPolByUsuario($UsuarioId, $Prefijo, $tablas) {
        return $this->db->jsonBuilder()->rawQuery("SELECT so.SolicitudId, so.UsuarioSolicitaId,s.SedeId, s.Nombre as Sede,ser.ServicioId,ser.Nombre as Servicio, 
        pds.EquipoId, IF(pds.HasNotEquipo = 1, pds.EquipoOtro, h.{$tablas->Equipo}) as Equipo, pds.Descripcion, h.{$tablas->Marca} as Marca, h.Modelo, h.{$tablas->Serie} as Serie, h.Inventario, 
        IF(pds.HasNotEquipo = 1, pds.Ubicacion, h.Ubicacion) as Ubicacion,  
        so.EstadoSolicitud, so.Estado, so.FechaFinalizacion, so.FechaSolicitud, so.IsFinalizada
        FROM {$Prefijo}_solicitud as so
        inner join {$Prefijo}_detallesolicitud as pds on pds.SolicitudId = so.SolicitudId
        inner join $tablas->sede as s on pds.SedeId = s.SedeId
        inner join $tablas->servicio as ser on pds.ServicioId = ser.ServicioId
        left join $tablas->hojavida as h on pds.EquipoId = h.HojaVidaId and pds.HasNotEquipo = 0
        where so.UsuarioSolicitaId = $UsuarioId order by so.SolicitudId desc;");
    }
    
    public function GetSolicitudPolById($SolicitudId, $Prefijo, $tablas) {
        return $this->db->jsonBuilder()->rawQuery("SELECT so.SolicitudId, so.UsuarioSolicitaId,s.SedeId, s.Nombre as Sede,ser.ServicioId,ser.Nombre as Servicio, 
        pds.EquipoId,IF(pds.HasNotEquipo = 1, pds.EquipoOtro, h.{$tablas->Equipo}) as Equipo, h.{$tablas->Marca} as Marca, h.Modelo, h.{$tablas->Serie} as Serie, h.Inventario, 
        IF(pds.HasNotEquipo = 1, pds.Ubicacion, h.Ubicacion) as Ubicacion, h.Foto,  
        so.EstadoSolicitud, so.Estado, so.FechaFinalizacion, so.FechaSolicitud, so.NombreUsuarioSolicita, so.CargoUsuarioSolicita, pds.Descripcion, pds.HasNotEquipo   
        FROM {$Prefijo}_solicitud as so
        inner join {$Prefijo}_detallesolicitud as pds on pds.SolicitudId = so.SolicitudId
        inner join $tablas->sede as s on pds.SedeId = s.SedeId
        inner join $tablas->servicio as ser on pds.ServicioId = ser.ServicioId
        left join $tablas->hojavida as h on pds.EquipoId = h.HojaVidaId and pds.HasNotEquipo = 0
        where so.SolicitudId = $SolicitudId;");
    }
    
    public function GetAllSolicitudesPol($Prefijo, $Mes, $Year, $tablas) {
        return $this->db->jsonBuilder()->rawQuery("SELECT so.SolicitudId, so.UsuarioSolicitaId, per.UsuarioBiomedicoId ,s.SedeId, s.Nombre as Sede,ser.ServicioId,ser.Nombre as Servicio, 
        pds.EquipoId,IF(pds.HasNotEquipo = 1, pds.EquipoOtro, h.{$tablas->Equipo}) as Equipo, pds.Descripcion, h.{$tablas->Marca} Marca, h.Modelo, h.{$tablas->Serie} Serie, h.Inventario, 
        IF(pds.HasNotEquipo = 1, pds.Ubicacion, h.Ubicacion) as Ubicacion,  
        so.EstadoSolicitud, so.Estado, so.FechaFinalizacion, so.FechaSolicitud, so.NombreUsuarioSolicita, so.IsVisto, so.IsFinalizada, so.NombreUsuarioFinaliza, 
        (select es.TipoEvento from {$Prefijo}_eventosolicitud as es where es.SolicitudId = so.SolicitudId order by es.EventoSolicitudId DESC limit 1)  as ESTADOSOLICITUD,
        (select es.EventoSolicitudId from {$Prefijo}_eventosolicitud as es where es.SolicitudId = so.SolicitudId and ProcesoId is not null order by es.EventoSolicitudId DESC limit 1)  as EventoSolicitudId, 
        (select es.ProcesoId from {$Prefijo}_eventosolicitud as es where es.SolicitudId = so.SolicitudId and ProcesoId is not null order by es.EventoSolicitudId DESC limit 1)  as ProcesoId 
        FROM {$Prefijo}_solicitud as so
        inner join {$Prefijo}_detallesolicitud as pds on pds.SolicitudId = so.SolicitudId
        inner join $tablas->sede as s on pds.SedeId = s.SedeId
        inner join $tablas->servicio as ser on pds.ServicioId = ser.ServicioId
        inner join ct_persona as per on per.UsuarioIntranetId = so.UsuarioSolicitaId
        left join $tablas->hojavida as h on pds.EquipoId = h.HojaVidaId and pds.HasNotEquipo = 0
        WHERE MONTH(so.FechaSolicitud) = $Mes and YEAR(so.FechaSolicitud) = $Year
        order by so.SolicitudId desc;");
    }
    
    public function CountSolicitudes($SedeId){
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM solicitud where Estado <> 'Completado' and Estado <> 'Cancelado' and SedeId=$SedeId;");
    }
}
