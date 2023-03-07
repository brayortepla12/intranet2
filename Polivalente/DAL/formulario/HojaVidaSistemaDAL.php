<?php

include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class HojaVidaSistemaDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateHojaVida($list) {
        $ids = $this->db->insertMulti("Sistemas_hojavida", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function IsInDB($Nombre) {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Existencia FROM sistemas_hojavida where Nombre = '$Nombre' and TipoArticulo <> 'Impresora' and Estado = 'Activo';");
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
        $ids = $this->db->insertMulti("Sistemas_accesorios", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function UpdateHojaVida($list, $id) {
        $this->db->where('HojaVidaId', $id);
        if ($this->db->update('Sistemas_hojavida', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function UpdateAccesorios($list, $id) {
        $this->db->where('AccesorioId', $id);
        if ($this->db->update('Sistemas_accesorios', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function GetHojaVidaByServicioQR($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId, h.Nombre as Equipo, h.Fabricante as Marca, h.Modelo,h.NSerial as Serie,
            h.TipoArticulo,h.Contador,ser.Nombre as SERVICIO, h.Ubicacion as UBICACION ,
            h.Inventario, h.Foto, h.Ubicacion
            from sistemas_hojavida as h
            STRAIGHT_JOIN servicio as ser on h.ServicioId = ser.ServicioId
            where h.ServicioId = $ServicioId;");
    }

    public function GetHojaVidaByServicio($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Fabricante, h.Ubicacion, h.Nombre as Equipo, h.Fabricante as Proveedor, h.Modelo,h.NSerial, h.TipoArticulo, 
            h.SO, h.SerieSO, h.Ram, 
            h.Procesador, h.Contador, h.Puerto, h.IP, h.DiscoDuro, h.RecomendacioneFabricante, h.Foto,se.Nombre as Sede,
            h.SedeId,h.ServicioId
            FROM Sistemas_hojavida as h 
            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
            STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
            where h.ServicioId = " . $ServicioId . " and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }

    public function GetHojaVidaByServicioPrint($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Ubicacion, h.Nombre as Equipo, h.Fabricante as Proveedor, h.Modelo,h.NSerial, h.TipoArticulo, 
            h.SO, h.SerieSO, h.Ram, 
            h.Procesador, h.Contador, h.Puerto, h.IP, h.DiscoDuro, h.RecomendacioneFabricante, h.Foto,se.Nombre as Sede,
            h.SedeId,h.ServicioId
            FROM Sistemas_hojavida as h 
            STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
            STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
            STRAIGHT_JOIN frecuenciamantenimiento as fm on fm.FrecuenciaMantenimientoId = h.FrecuenciaMantenimientoId
            STRAIGHT_JOIN frecuenciamantenimiento as fc on fc.FrecuenciaMantenimientoId = h.FrecuenciaCalibracionId
            where h.ServicioId = $ServicioId and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }

    public function GetHojaVidas() {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId, h.Ubicacion, h.Nombre as Equipo, h.Fabricante, h.Modelo,h.NSerial,
                            h.SedeId,h.ServicioId, h.Estado  
                            FROM Sistemas_hojavida as h 
                            where h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }

    public function GetHojaVidaBySedeId($SedeId, $Estado) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Ubicacion, h.Nombre as Equipo, h.NSerial,
        se.Nombre as Sede, h.TipoArticulo, h.Fabricante, h.SO, h.Estado  
        FROM Sistemas_hojavida as h 
        STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
        STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
        where h.SedeId = " . $SedeId . " and h.Estado= '$Estado' order by h.HojaVidaId desc;");
    }
    
    public function GetHojaVidaBySedeId_Servicio($SedeId, $ServicioId, $Estado) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Ubicacion, h.Nombre as Equipo, h.NSerial,
        se.Nombre as Sede, h.TipoArticulo, h.Fabricante, h.SO, h.Estado
        FROM sistemas_hojavida as h 
        STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
        STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
        where h.ServicioId = " . $ServicioId . " and h.Estado= '$Estado' order by h.HojaVidaId desc;");
    }

    public function GetImpresoras($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT * FROM sistemas_hojavida where TipoArticulo= 'Impresora' and  ServicioId=$ServicioId;");
    }

    public function GetHojaVidaByHojaVidaId($Sistemas_hojavidaId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId, h.SedeId, h.FechaUltimoMantenimiento, h.FechaCalibracion, h.ServicioId, 
            h.FrecuenciaMantenimientoId, fm.Nombre as FrecuenciaMantenimiento, fc.Nombre as FrecuenciaCalibracion, 
            fc.FrecuenciaMantenimientoId as FrecuenciaCalibracionId,s.Nombre as Servicio, h.Ubicacion, 
            h.Nombre, h.Fabricante, h.Modelo,h.NSerial, h.FechaInstalacion, h.Tipo, h.SO, h.SerieSO, 
            h.Foto,se.Nombre as Sede, h.TipoArticulo, h.RecomendacioneFabricante,h.Ram,h.Procesador,h.DiscoDuro,
             h.SedeId,h.ServicioId,
                h.Puerto, h.Contador, h.IP, h.IsSistema, h.LicenciaOffice, h.LicenciaWindows, h.LicenciaAntivirus  
             FROM Sistemas_hojavida as h 
             STRAIGHT_JOIN servicio as s on h.ServicioId = s.ServicioId
             STRAIGHT_JOIN sede as se on h.SedeId = se.SedeId
             STRAIGHT_JOIN frecuenciamantenimiento as fm on fm.FrecuenciaMantenimientoId = h.FrecuenciaMantenimientoId
             STRAIGHT_JOIN frecuenciamantenimiento as fc on fc.FrecuenciaMantenimientoId = h.FrecuenciaCalibracionId
             where h.HojaVidaId = " . $Sistemas_hojavidaId . " and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }

    public function GetAccesoriosByHojaVidaId($Sistemas_hojavidaId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT * FROM Sistemas_accesorios where FechaBaja IS NULL and HojaVidaId = $Sistemas_hojavidaId and Estado = 'Activo';");
    }

    public function GetReporteBySerie($Serie) {
        $this->db->where("Serie", $Serie);
        return $this->db->objectBuilder()->getOne("Sistemas_hojavida");
    }

    public function GetNHojaVida() {
        return $this->db->jsonBuilder()->rawQuery("SELECT hojavidaId FROM Sistemas_hojavida order by hojavidaId desc limit 1;");
    }

    public function CountHojaVidas() {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM Sistemas_hojavida where Estado = 'Activo';");
    }

    public function CountHojaVidaBySede($SedeId) {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM Sistemas_hojavida where Estado = 'Activo' and SedeId = $SedeId;");
    }

}
