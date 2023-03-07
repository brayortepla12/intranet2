<?php
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class ActividadesRondaDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function CreateActividadesRonda($list) {
        $ids = $this->db->insertMulti("ActividadesRonda", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function UpdateActividadesRonda($list, $id) {
        $this->db->where ('ActividadesRondaId', $id);
        if ($this->db->update('ActividadesRonda', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function GetActividadesRondaByUsuario($UsuarioId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT so.ActividadesRondaId, so.SolicitanteId,s.SedeId, s.Nombre as Sede,ser.ServicioId,ser.Nombre as Servicio, 
                so.EquipoId,h.Equipo, h.Marca, h.Modelo, h.Serie, h.Inventario, h.Ubicacion,h.Foto, 
                u.UsuarioId, u.NombreCompleto as Solicitante, 
                so.Descripcion,so.Estado, so.FechaFinalizacion, so.FechaActividadesRonda
                FROM solicitud as so
                STRAIGHT_JOIN sede as s on so.SedeId = s.SedeId
                STRAIGHT_JOIN servicio as ser on so.ServicioId = ser.ServicioId
                STRAIGHT_JOIN hojavida as h on so.EquipoId = h.HojaVidaId
                STRAIGHT_JOIN usuario as u on so.SolicitanteId = u.UsuarioId
                where so.SolicitanteId = $UsuarioId order by so.ActividadesRondaId desc;");
    }
    
    public function GetActividadesRondaByRondaId($RondaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * from ActividadesRonda
            where RondaId = $RondaId
               order by ActividadesRondaId desc;");
    }
    
    public function GetAllActividadesRondas() {
        return $this->db->jsonBuilder()->rawQuery("SELECT * from ActividadesRonda
               order by ActividadesRondaId desc;");
    }
    
    public function CountActividadesRondaes($SedeId){
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM solicitud where Estado <> 'Completado' and Estado <> 'Cancelado' and SedeId=$SedeId;");
    }
}
