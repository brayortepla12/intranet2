<?php
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";
class HojaVidaAmbulanciaDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
       $dotenv->load();
       $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
       $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function CreateHojaVida($list) {
        $ids = $this->db->insertMulti("ambulancia_hojavida", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function UpdateHojaVida($list, $id) {
        $this->db->where ('HojaVidaId', $id);
        if ($this->db->update('ambulancia_hojavida', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function GetUsuariosANotificar() {
        return $this->db->objectBuilder()->rawQuery("select u.Email, u.NombreCompleto from usuario as u 
        inner join ambulancia_notificarusuario as nu on nu.UsuarioId = u.UsuarioId");
    }
    
    public function GetMovilesProxVencerSoatTec() {
        return $this->db->objectBuilder()->rawQuery("select h.Placa, h.FechaSoat, h.FechaTecnomecanica,  datediff(h.FechaSoat, now()) as DSoat, datediff(h.FechaTecnomecanica, now()) as DTec 
        from ambulancia_hojavida as h where datediff(h.FechaSoat, now()) <= 15 or datediff(h.FechaTecnomecanica, now()) <= 15 and h.EstadoMovil = 'Rodando' order by h.Placa");
    }
    
    public function GetHojaVidaByServicio($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Placa, h.Marca, h.Modelo,h.Linea, h.Capacidad, h.ClaseVehiculo, h.TipoCarroceria, h.LicenciaTransito, h.Soat, 
                            h.Cilindrada, h.Color, h.Motor, h.Serie, h.Combustible, h.Foto,se.Nombre as Sede, h.SedeId,h.ServicioId FROM ambulancia_hojavida as h 
                            inner join servicio as s on h.ServicioId = s.ServicioId
                            inner join sede as se on h.SedeId = se.SedeId
                            where h.ServicioId = " . $ServicioId  . " and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }
    
    public function GetALLHojas() {
        return $this->db->objectBuilder()->rawQuery("SELECT h.HojaVidaId, h.Placa, h.EstadoMovil,DATEDIFF(FechaSoat, now()) VSoat,  DATEDIFF(FechaTecnomecanica, now()) VTecnomecanica, 
            (select km.Km from ambulancia_km as km where km.HojaVidaId = h.HojaVidaId order by km.KmId DESC Limit 1) as KmActual
                 FROM polivalente.ambulancia_hojavida as h where Estado= 'Activo' order by Placa;");
    }
    
    public function GetHojaVidaBySedeId($SedeId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Linea, h.Placa, h.Marca, h.Modelo, h.LicenciaTransito, h.Soat
            ,se.Nombre as Sede, h.SedeId,h.ServicioId
                            FROM ambulancia_hojavida as h 
                            inner join servicio as s on h.ServicioId = s.ServicioId
                            inner join sede as se on h.SedeId = se.SedeId
                            where h.SedeId = " . $SedeId  . " and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }
    
    public function GetHojaVidaByHojaVidaId($HojaVidaId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId,s.Nombre as Servicio, h.Placa, h.Marca, h.Modelo,h.Linea, h.Capacidad, h.ClaseVehiculo, h.TipoCarroceria, h.LicenciaTransito, h.Soat, 
                            h.Cilindrada, h.Color, h.Motor, h.Serie, h.Combustible, h.Foto,se.Nombre as Sede, h.SedeId, h.ServicioId, h.FechaSoat, h.FechaTecnomecanica,
                            (select km.Km from ambulancia_km as km where km.HojaVidaId = h.HojaVidaId order by km.KmId DESC Limit 1) as KmActual
                            FROM ambulancia_hojavida as h 
                            inner join servicio as s on h.ServicioId = s.ServicioId
                            inner join sede as se on h.SedeId = se.SedeId
                            where h.HojaVidaId = " . $HojaVidaId  . " and h.Estado= 'Activo' order by h.HojaVidaId desc;");
    }
    public function GetReporteBySerie($Serie) {
        $this->db->where("Serie", $Serie);
        return $this->db->objectBuilder()->getOne("HojaVida");
    }
    
    public function GetNHojaVida() {
        return $this->db->jsonBuilder()->rawQuery("SELECT HojaVidaId FROM ambulancia_hojavida order by HojaVidaId desc limit 1;");
    }
    
    public function CountHojaVidas() {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM ambulancia_hojavida where Estado = 'Activo';");
    }
    public function CountHojaVidasBySede($SedeId) {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM ambulancia_hojavida where Estado = 'Activo' and SedeId = $SedeId;");
    }
}
