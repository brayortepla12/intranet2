<?php
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class HojaVidaDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function CreateHojaVida($list) {
        $ids = $this->db->insertMulti("HojaVida", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function UpdateHojaVida($list, $id) {
        $this->db->where ('HojaVidaId', $id);
        if ($this->db->update('hojavida', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function GetHojaVidaByServicioBIOQR($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId, h.Equipo, h.Marca, h.Modelo, h.Serie, ser.Nombre as Servicio, h.Ubicacion 
        from biomedico.hojavida as h 
        STRAIGHT_JOIN servicio as ser on h.ServicioId = ser.ServicioId
        where h.Estado <> 'Inactivo' and h.ServicioId = $ServicioId order by h.SedeId, ser.Nombre, h.Ubicacion, h.Equipo");
    }
    
    public function GetHojaVidaByServicioQR($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId, h.Equipo, h.Marca, h.Modelo, h.Serie,
        h.Inventario, h.Foto, 
        ser.Nombre as Servicio, h.Ubicacion 
        from hojavida as h 
        STRAIGHT_JOIN servicio as ser on h.ServicioId = ser.ServicioId
        where h.Estado <> 'Inactivo' and h.ServicioId = $ServicioId order by h.SedeId, ser.Nombre, h.Ubicacion, h.Equipo");
    }
    
    public function GetHojaVidaByServicio($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Ubicacion, h.Equipo, h.Marca, h.Modelo,h.Serie, h.Capacidad, h.RegSanitario, h.FechaAdquisicion, h.Garantia, 
                            h.Peso, h.Presion, h.Voltaje, h.Temperatura, h.Amperaje, h.Frecuencia, h.Potencia, h.Foto,se.Nombre as Sede, 
                            h.Inventario, h.RecomendacioneFabricante, h.Accesorios,  p.Nombre as Proveedor,
                            h.SedeId,h.ServicioId  
                            FROM hojavida as h 
                            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
                            STRAIGHT_JOIN proveedor as p on h.ProveedorId = p.ProveedorId
                            STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
                            where h.ServicioId = " . $ServicioId  . " and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }
    
    public function GetHojaVidaByServicioId($ServicioId) {
        return $this->db->objectBuilder()->rawQuery("select h.HojaVidaId, h.Equipo, h.Marca, h.Modelo, h.Serie, h.Ubicacion
                            FROM hojavida as h 
                            where h.ServicioId = " . $ServicioId  . " and h.Estado= 'Activo';");
    }
    
    public function GetHojaVidaByServicioIdWithTA($ServicioId, $Tablas) {
        return $this->db->objectBuilder()->rawQuery("select h.HojaVidaId, h.{$Tablas->Equipo} as Equipo, h.{$Tablas->Marca} as Marca, h.Modelo, h.{$Tablas->Serie} as Serie, h.Ubicacion
                            FROM $Tablas->hojavida as h 
                            where h.ServicioId = " . $ServicioId  . " and h.Estado= 'Activo';");
    }
    
    
    public function GetHojaVidaByServicioPrint($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId, h.SedeId, h.FechaInstalacion, h.FechaCalibracion, h.ProveedorId, h.ServicioId, h.FrecuenciaMantenimientoId, fm.Nombre as FrecuenciaMantenimiento, fc.Nombre as FrecuenciaCalibracion, fc.FrecuenciaMantenimientoId as FrecuenciaCalibracionId, p.Nombre as Proveedor ,s.Nombre as Servicio, h.Ubicacion, h.Equipo, h.Marca, h.Modelo,h.Serie, h.Capacidad, h.RegSanitario, h.FechaAdquisicion, h.Garantia, 
                            h.Peso, h.Presion, h.Voltaje, h.Temperatura, h.Amperaje, h.Frecuencia, h.Potencia, h.Foto,se.Nombre as Sede, h.TipoRiesgo, 
                            h.Inventario, h.RecomendacioneFabricante, h.Accesorios,  p.Nombre as Proveedor,
                            h.SedeId,h.ServicioId  
                            FROM hojavida as h 
                            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
                            STRAIGHT_JOIN proveedor as p on h.ProveedorId = p.ProveedorId
                            STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
                            STRAIGHT_JOIN frecuenciamantenimiento as fm on fm.FrecuenciaMantenimientoId = h.FrecuenciaMantenimientoId
                            STRAIGHT_JOIN frecuenciamantenimiento as fc on fc.FrecuenciaMantenimientoId = h.FrecuenciaCalibracionId
                            where h.ServicioId = $ServicioId and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }
    
    public function GetHojaVidas() {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId, h.Ubicacion, h.Equipo, h.Marca, h.Modelo,h.Serie,
                            h.SedeId,h.ServicioId, h.Estado  
                            FROM hojavida as h 
                            where h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }
    
    public function GetHojaVidaBySedeId($SedeId, $Estado) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Ubicacion, h.Equipo, h.Serie,se.Nombre as Sede, h.Estado  
                            FROM hojavida as h 
                            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
                            STRAIGHT_JOIN proveedor as p on h.ProveedorId = p.ProveedorId
                            STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
                            where h.SedeId = " . $SedeId  . " and h.Estado= '$Estado' order by h.HojaVidaId desc;");
    }
    
    public function GetHojaVidaBySedeId_Servicio($SedeId, $ServicioId, $Estado) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Ubicacion, h.Equipo, h.Serie,se.Nombre as Sede, h.Estado
                            FROM hojavida as h 
                            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
                            STRAIGHT_JOIN proveedor as p on h.ProveedorId = p.ProveedorId
                            STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
                            where h.ServicioId = " . $ServicioId  . " and h.Estado= '$Estado' order by h.HojaVidaId desc;");
    }
    
    public function GetHojaVidaByHojaVidaId($HojaVidaId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId, h.SedeId, h.FechaInstalacion, h.FechaCalibracion, h.ProveedorId, h.ServicioId, h.FrecuenciaMantenimientoId, fm.Nombre as FrecuenciaMantenimiento, fc.Nombre as FrecuenciaCalibracion, fc.FrecuenciaMantenimientoId as FrecuenciaCalibracionId, p.Nombre as Proveedor ,s.Nombre as Servicio, h.Ubicacion, h.Equipo, h.Marca, h.Modelo,h.Serie, h.Capacidad, h.RegSanitario, h.FechaAdquisicion, h.Garantia, 
                            h.Peso, h.Presion, h.Voltaje, h.Temperatura, h.Amperaje, h.Frecuencia, h.Potencia, h.Foto,se.Nombre as Sede, h.TipoRiesgo, 
                            h.Inventario, h.RecomendacioneFabricante, h.Accesorios,  p.Nombre as Proveedor,
                            h.SedeId,h.ServicioId  
                            FROM hojavida as h 
                            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
                            STRAIGHT_JOIN proveedor as p on h.ProveedorId = p.ProveedorId
                            STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
                            STRAIGHT_JOIN frecuenciamantenimiento as fm on fm.FrecuenciaMantenimientoId = h.FrecuenciaMantenimientoId
                            STRAIGHT_JOIN frecuenciamantenimiento as fc on fc.FrecuenciaMantenimientoId = h.FrecuenciaCalibracionId
                            where h.HojaVidaId = " . $HojaVidaId  . " and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }
    
    public function GetHojaVidaBySedeIdFULL($SedeId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId, se.SedeId, s.ServicioId,s.Nombre as Servicio, h.Ubicacion, h.Equipo, h.Serie,se.Nombre as Sede, 
                            (SELECT count(*) Total FROM reporte where EquipoId = h.HojaVidaId and EstadoReporte = 'Activo') as TotalReportes
                            FROM hojavida as h 
                            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
                            STRAIGHT_JOIN proveedor as p on h.ProveedorId = p.ProveedorId
                            STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
                            where h.SedeId = " . $SedeId  . " and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }
    
    public function GetReporteBySerie($Serie) {
        $this->db->where("Serie", $Serie);
        return $this->db->objectBuilder()->getOne("HojaVida");
    }
    
    public function GetNHojaVida() {
        return $this->db->jsonBuilder()->rawQuery("SELECT HojaVidaId FROM hojavida order by HojaVidaId desc limit 1;");
    }
    
    public function CountHojaVidas() {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM hojavida where Estado = 'Activo';");
    }
    public function CountHojaVidasBySede($SedeId) {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM hojavida where Estado = 'Activo' and SedeId = $SedeId;");
    }
}
