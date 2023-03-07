<?php
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class ReporteAmbulanciaDAL {
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
        $ids = $this->db->insertMulti("ambulancia_reporte", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function GetReporteBySolicitudMantenimientoId($SolicitudMantenimientoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM ambulancia_reporte where SolicitudMantenimientoId = $SolicitudMantenimientoId order by ReporteId DESC;");
    }
    
    public function GetCronogramaByMovil($HojaVidaId) {
        return $this->db->objectBuilder()->rawQuery("select t.*, t.FrecuenciaKm + t.KmUltimoCambio as ProximoCambio from (
            select  r.ReporteId, r.HojaVidaId, d.UrlImagen,d.FrecuenciaKm, d.Descripcion, d.DetalleId, r.Km as KmUltimoCambio from ambulancia_reporte as r 
        left join ambulancia_detallereporte as dr on  r.ReporteId = dr.ReporteId
        left join ambulancia_detalle as d on  dr.DetalleId = d.DetalleId
        where r.HojaVidaId = $HojaVidaId 
        order by r.ReporteId, dr.DetalleReporteId DESC) as t
        where (select rr.ReporteId from ambulancia_reporte as rr where rr.HojaVidaId = $HojaVidaId and 
        (select count(*) from ambulancia_detallereporte as drr where drr.ReporteId = rr.ReporteId) > 0 order by rr.ReporteId DESC limit 1) = t.ReporteId 
        group by t.HojaVidaId, t.DetalleId order by t.Descripcion;");
    }
    
    public function GetAllReporteAmbulanciasBySede($SedeId) {
        return $this->db->jsonBuilder()->rawQuery("select r.ReporteId, r.TipoMantenimiento, r.LastKm, r.Km, r.Fecha, h.Placa, r.Descripcion from ambulancia_reporte as r 
        inner join ambulancia_hojavida as h on h.HojaVidaId = r.HojaVidaId
        where r.SedeId = $SedeId and r.TipoMantenimiento <> 'EXTERNO' order by r.Fecha DESC");
    }
    
    public function GetReporteByRango($From, $To) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.CostoMaterial,r.CreatedAt,r.CreatedBy,
            r.Descripcion,r.Down,r.Estado,r.Estado,r.Fecha,r.FechaTerminacion,
            r.HojaVidaId,r.InventarioLst,r.KM,r.NFactura,r.Notas,r.Referencia,
            r.ReporteId,h.Placa 
            FROM reporte as r
            inner join HojaVida as h on r.HojaVidaId = h.HojaVidaId
            where r.Fecha >= '$From' and r.Fecha <= '$To';");
    }
    
    public function GetLastReporteByHojaVidaId($HojaVidaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, s.Nombre as Sede, r.Fecha, 
            ser.Nombre as Servicio, h.Placa, h.Marca, h.Modelo, h.Linea, h.Serie, 
            r.Referencia, r.SistemaId, r.NFactura,r.Down,r.FechaTerminacion,r.CostoMaterial,
            r.Descripcion, r.Notas, r.InventarioLst, r.KM,r.Estado, r.CreatedBy
            FROM reporte as r
            inner join sede as s on r.SedeId = s.SedeId
            inner join servicio as ser on r.ServicioId = ser.ServicioId
            inner join ambulancia_hojavida as h on r.HojaVidaId = h.HojaVidaId
            where r.HojaVidaId = $HojaVidaId and r.KM <> '' order by r.ReporteId desc Limit 1;");
    }
    
    public function GetReporteAmbulanciaById($ReporteAmbulanciaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM ambulancia_reporte where ReporteId = $ReporteAmbulanciaId;");
    }
    
    public function GetReporteByEquipoId($EquipoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.ReporteId, r.TipoMantenimiento, r.KM, r.Fecha, r.Descripcion
            FROM ambulancia_reporte as r
            where r.HojaVidaId = $EquipoId order by r.KM ASC;");
    }
    
    public function GetCronogramaReporteAmbulanciaByEquipoId($EquipoId, $DetalleId) {
        return $this->db->objectBuilder()->rawQuery("select d.DetalleId, d.Descripcion, d.FrecuenciaKm, r.Km, (select km.Km from ambulancia_km as km where km.HojaVidaId = h.HojaVidaId order by km.KmId DESC Limit 1) as KmActual from ambulancia_detalle as d 
        inner join ambulancia_hojavida as h on h.HojaVidaId = $EquipoId
        inner join ambulancia_reporte as r on r.HojaVidaId = h.HojaVidaId 
        inner join ambulancia_detallereporte as dr on dr.DetalleId = d.DetalleId and r.ReporteId = dr.ReporteId
        where d.DetalleId = $DetalleId
        order by r.Km");
    }
    
    public function UpdateReporte($list, $id) {
        $this->db->where ('ReporteId', $id);
        if ($this->db->update('ambulancia_reporte', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
