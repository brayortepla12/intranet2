<?php

include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class HojaVidaSSTDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateHojaVida($list) {
        $ids = $this->db->insertMulti("SST_hojavida", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function ProximosVencer() {
        return $this->db->objectBuilder()->rawQuery("SELECT h.*, s.Nombre as Sede, ser.Nombre as Servicio FROM sst_hojavida as h
        inner join sede as s on h.SedeId = s.SedeId
        inner join servicio as ser on h.ServicioId = ser.ServicioId
         where now() >= SUBDATE(FechaVencimiento, INTERVAL 15 DAY) and now() <= FechaVencimiento ");
    }
    
    public function CountComputadoresBySede($SedeId) {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM sistemas_hojavida where Estado = 'Activo' and (TipoArticulo = 'Computador Torre' || TipoArticulo = 'Computador Portatil')  and SedeId = $SedeId;");
    }
    
    public function CountHojaVidasBySede($SedeId) {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM sistemas_hojavida where Estado = 'Activo' and SedeId = $SedeId;");
    }
    
    
    
    public function CountImpresorasBySede($SedeId) {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM sistemas_hojavida where Estado = 'Activo' and TipoArticulo = 'Impresora'  and SedeId = $SedeId;");
    }
    
    public function CreateAccesorios($list) {
        $ids = $this->db->insertMulti("SST_accesorios", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function UpdateHojaVida($list, $id) {
        $this->db->where('HojaVidaId', $id);
        if ($this->db->update('SST_hojavida', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function UpdateAccesorios($list, $id) {
        $this->db->where('AccesorioId', $id);
        if ($this->db->update('SST_accesorios', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function GetHojaVidaByServicio($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Ubicacion, h.Nombre as Equipo, 
            h.Fabricante as Proveedor, h.Modelo,h.NSerial, h.TipoArticulo, 
            h.Sector, h.ClaseExtintor, h.RecomendacioneFabricante, h.Foto,se.Nombre as Sede,
            h.SedeId,h.ServicioId, h.NumeroExtintor, h.FechaInstalacion, h.FechaRecarga, h.FechaVencimiento 
            FROM SST_hojavida as h 
            inner join servicio as s on h.ServicioId = s.ServicioId
            inner join sede as se on h.SedeId = se.SedeId
            where h.ServicioId = " . $ServicioId . " and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }

    public function GetHojaVidaByServicioPrint($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Ubicacion, h.Nombre as Equipo, h.Fabricante as Proveedor, h.Modelo,h.NSerial, h.TipoArticulo, 
            h.Sector, h.RecomendacioneFabricante, h.Foto,se.Nombre as Sede,
            h.SedeId,h.ServicioId
            FROM SST_hojavida as h 
            inner join servicio as s on h.ServicioId = s.ServicioId
            inner join sede as se on h.SedeId = se.SedeId
            inner join frecuenciamantenimiento as fm on fm.FrecuenciaMantenimientoId = h.FrecuenciaMantenimientoId
            inner join frecuenciamantenimiento as fc on fc.FrecuenciaMantenimientoId = h.FrecuenciaCalibracionId
            where h.ServicioId = $ServicioId and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }

    public function GetHojaVidas() {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId, h.Ubicacion, h.Nombre as Equipo, h.Fabricante, h.Modelo,h.NSerial,
                            h.SedeId,h.ServicioId, h.Estado  
                            FROM SST_hojavida as h 
                            where h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }

    public function GetHojaVidaBySedeId($SedeId, $Estado) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Ubicacion, h.Nombre as Equipo, h.NSerial,
        se.Nombre as Sede, h.TipoArticulo, h.Fabricante, h.Sector, h.Estado  
        FROM SST_hojavida as h 
        inner join servicio as s on h.ServicioId = s.ServicioId
        inner join sede as se on h.SedeId = se.SedeId
        where h.SedeId = " . $SedeId . " and h.Estado= '$Estado' order by h.HojaVidaId desc;");
    }
    
    public function GetHojaVidaBySedeId_Cronograma($SedeId, $Estado) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Ubicacion, h.Nombre as Equipo, h.NSerial,
        se.Nombre as Sede, h.TipoArticulo, h.Fabricante, h.Sector, h.Estado, h.NumeroExtintor, h.FechaInstalacion , h.ClaseExtintor, h.FechaRecarga, h.FechaVencimiento  
        FROM SST_hojavida as h 
        inner join servicio as s on h.ServicioId = s.ServicioId
        inner join sede as se on h.SedeId = se.SedeId
        where h.SedeId = " . $SedeId . " and h.Estado= '$Estado' order by h.HojaVidaId desc;");
    }
    
    public function GetHojaVidaBySedeId_Servicio($SedeId, $ServicioId, $Estado) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Ubicacion, h.Nombre as Equipo, h.NSerial,
        se.Nombre as Sede, h.TipoArticulo, h.Fabricante, h.Sector, h.Estado
        FROM SST_hojavida as h 
        inner join servicio as s on h.ServicioId = s.ServicioId
        inner join sede as se on h.SedeId = se.SedeId
        where h.ServicioId = " . $ServicioId . " and h.Estado= '$Estado' order by h.HojaVidaId desc;");
    }
    
    public function GetHojaVidaBySedeId_Servicio_Cronograma($SedeId, $ServicioId, $Estado) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Ubicacion, h.Nombre as Equipo, h.NSerial,
        se.Nombre as Sede, h.TipoArticulo, h.Fabricante, h.Sector, h.Estado,  h.NumeroExtintor, h.ClaseExtintor, h.FechaRecarga, h.FechaVencimiento
        FROM SST_hojavida as h 
        inner join servicio as s on h.ServicioId = s.ServicioId
        inner join sede as se on h.SedeId = se.SedeId
        where h.ServicioId = " . $ServicioId . " and h.Estado= '$Estado' order by h.HojaVidaId desc;");
    }

    public function GetImpresoras($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT * FROM sistemas_hojavida where TipoArticulo= 'Impresora' and  ServicioId=$ServicioId;");
    }

    public function GetHojaVidaByHojaVidaId($SST_hojavidaId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId, h.SedeId, h.FechaRecarga, h.FechaVencimiento, h.ServicioId, 
            s.Nombre as Servicio, h.Ubicacion, 
            h.Nombre, h.Fabricante, h.Modelo,h.NSerial, h.Tipo, h.Sector, h.ClaseExtintor, 
            h.Foto,se.Nombre as Sede, h.TipoArticulo, h.RecomendacioneFabricante,
             h.SedeId,h.ServicioId, h.NumeroExtintor, h.FechaInstalacion   
             FROM SST_hojavida as h 
             inner join servicio as s on h.ServicioId = s.ServicioId
             inner join sede as se on h.SedeId = se.SedeId
             where h.HojaVidaId = " . $SST_hojavidaId . " and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }

    public function GetReporteBySerie($Serie) {
        $this->db->where("Serie", $Serie);
        return $this->db->objectBuilder()->getOne("SST_hojavida");
    }

    public function GetNHojaVida() {
        return $this->db->jsonBuilder()->rawQuery("SELECT hojavidaId FROM SST_hojavida order by hojavidaId desc limit 1;");
    }

    public function CountHojaVidas() {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM SST_hojavida where Estado = 'Activo';");
    }

    public function CountHojaVidaBySede($SedeId) {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM SST_hojavida where Estado = 'Activo' and SedeId = $SedeId;");
    }

}
