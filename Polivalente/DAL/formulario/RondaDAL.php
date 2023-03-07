<?php
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class RondaDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function CreateRonda($list) {
        $ids = $this->db->insertMulti("Ronda", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function UpdateRonda($list, $id) {
        $this->db->where ('RondaId', $id);
        if ($this->db->update('Ronda', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function GetRondaByUsuario($UsuarioId) {
        return NULL;
    }
    
    public function GetRondaById($RondaId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT s.Nombre as Sede, se.Nombre as Servicio, r.CreatedAt, r.CreatedBy, r.Estado, 
        r.Fecha, r.ModifiedAt, r.ModifiedBy, r.NombreJefeArea,r.Observaciones,r.Realizo,
        r.RondaId, r.SedeId,r.ServicioId 
        FROM ronda as r
        STRAIGHT_JOIN Sede as s on r.SedeId = s.SedeId
        STRAIGHT_JOIN servicio as se on r.ServicioId = se.ServicioId
        where r.RondaId = $RondaId;");
    }
    
    public function DeleteRonda($RondaId) {
        $this->db->rawQuery("delete au.* from actividadesusuario as au 
        STRAIGHT_JOIN actividadesronda as ar on au.ActividadesRondaId = ar.ActividadesRondaId
        where ar.RondaId = '$RondaId';
        ");
        $this->db->rawQuery("delete from actividadesronda where RondaId = '$RondaId';");
        $this->db->rawQuery("delete from ronda where RondaId = '$RondaId';");
        
    }
    
    public function GetAllRondas($UsuarioId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT s.Nombre as Sede, se.Nombre as Servicio, r.CreatedAt, r.CreatedBy, r.Estado, 
        r.Fecha, r.ModifiedAt, r.ModifiedBy, r.NombreJefeArea,r.Observaciones,r.Realizo,
        r.RondaId, r.SedeId,r.ServicioId 
        FROM ronda as r
        STRAIGHT_JOIN Sede as s on r.SedeId = s.SedeId
        STRAIGHT_JOIN servicio as se on r.ServicioId = se.ServicioId
        STRAIGHT_JOIN serviciousuario as su on r.ServicioId = su.ServicioId
        where su.UsuarioId = $UsuarioId
        order by r.RondaId desc;");
    }
    
    public function GetAllRondasHistorico($UsuarioId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT s.Nombre as Sede, se.Nombre as Servicio, r.CreatedAt, r.CreatedBy, r.Estado, 
        r.Fecha, r.ModifiedAt, r.ModifiedBy, r.NombreJefeArea,r.Observaciones,r.Realizo,
        r.RondaId, r.SedeId,r.ServicioId 
        FROM ronda as r
        STRAIGHT_JOIN Sede as s on r.SedeId = s.SedeId
        STRAIGHT_JOIN servicio as se on r.ServicioId = se.ServicioId
        STRAIGHT_JOIN serviciousuario as su on r.ServicioId = su.ServicioId
        where su.UsuarioId = $UsuarioId
        order by r.RondaId desc;");
    }
    
    public function GetAllRondasLite($UsuarioId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT se.Nombre as Servicio, r.CreatedAt, r.CreatedBy, r.Estado,
        FROM ronda as r
        STRAIGHT_JOIN servicio as se on r.ServicioId = se.ServicioId
        STRAIGHT_JOIN serviciousuario as su on r.ServicioId = su.ServicioId
        where su.UsuarioId = $UsuarioId
        order by r.RondaId desc;");
    }
    
    public function GetAllRondasByFecha($Fecha, $UsuarioId) {
        return $this->db->jsonBuilder()->rawQuery("SELECT s.Nombre as Sede, se.Nombre as Servicio, r.CreatedAt, r.CreatedBy, r.Estado, 
        r.Fecha, r.ModifiedAt, r.ModifiedBy, r.NombreJefeArea,r.Observaciones,r.Realizo,
        r.RondaId, r.SedeId,r.ServicioId , r.Cumplimiento, r.ObservacionSeguimiento
        FROM ronda as r
        STRAIGHT_JOIN Sede as s on r.SedeId = s.SedeId
        STRAIGHT_JOIN servicio as se on r.ServicioId = se.ServicioId
        STRAIGHT_JOIN serviciousuario as su on r.ServicioId = su.ServicioId
        where su.UsuarioId = $UsuarioId and SUBSTRING_INDEX(SUBSTRING_INDEX(r.Fecha, ' ', 1), ' ', -1) = '$Fecha'
        order by Servicio;");
    }
    
    public function CountRondaes($SedeId){
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM solicitud where Estado <> 'Completado' and Estado <> 'Cancelado' and SedeId=$SedeId;");
    }
}
