<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class RondaSistemaServicioDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct()
    {
       $this->db->disconnect();
    }
    public function getAll() {
        return $this->db->jsonBuilder()->get("Sistemas_Ronda");
    }
    
    public function GetRondaSistema_Detalle($DetalleRondaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * From sistemas_detalleronda
                where DetalleRondaId = $DetalleRondaId;");
    }
    
    public function GetByRondaId($RondaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * From Sistemas_Ronda
                where RondaId = $RondaId;");
    }
    
    public function GetServiciosByRondaId($RondaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT DISTINCT s.ServicioId, s.Nombre, s.SedeId, s.Piso FROM servicio as s
        inner join sistemas_rondaserviciousuario as rs on s.ServicioId = rs.ServicioId
        where rs.RondaId = $RondaId;");
    }
    
    public function GetUsuariosByRondaId($RondaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT DISTINCT u.UsuarioId, u.NombreCompleto, u.NombreUsuario FROM usuario as u
        inner join sistemas_rondaserviciousuario as ru on u.UsuarioId = ru.UsuarioId
        where ru.RondaId = $RondaId;");
    }
    
    public function Verificar($RondaId, $ServicioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * from sistemas_rondaserviciousuario where RondaId = $RondaId and ServicioId = $ServicioId;");
    }
    
    public function CreateRondaSistemaServicio($list) {
        $ids = $this->db->insertMulti("RondaSistemaServicio", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateRondaSistema($list) {
        $ids = $this->db->insertMulti("Sistemas_Ronda", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateRondaSistema_Detalle($list) {
        $ids = $this->db->insertMulti("sistemas_detalleronda", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateRondaSistemaServicioUsuario($list) {
        $ids = $this->db->insertMulti("Sistemas_RondaServicioUsuario", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function GetRondaSistemaServicio($Nombre) {
        $this->db->where("Nombre", $Nombre);
        return $this->db->objectBuilder()->get("Sistemas_RondaServicioUsuario");
    }
    
    public function GetRondaSistemaServicioByUsuario($UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT distinct r.RondaId, r.Nombre, r.Tipo, r.Estado FROM sistemas_ronda as r
        inner join sistemas_rondaserviciousuario as rsu on r.RondaId = rsu.RondaId  
        where rsu.UsuarioId = $UsuarioId and r.Estado = 'Activo';");
    }
    
    public function GetRondaSistemaServicioByUsuario_Ronda($UsuarioId, $RondaId) {
        return $this->db->objectBuilder()->rawQuery("select rsu.RondaServicioUsuarioId, s.Nombre, dr.DetalleRondaId, dr.Observacion1, dr.Observacion2, 
            dr.Fecha, dr.Estado, r.Nombre,r.Tipo,r.RondaId 
            from sistemas_rondaserviciousuario as rsu
        inner join sistemas_ronda as r on rsu.RondaId = r.RondaId
        inner join servicio as s on rsu.ServicioId = s.ServicioId
        left join sistemas_detalleronda as dr on rsu.RondaServicioUsuarioId = dr.RondaServicioUsuarioId
        where rsu.RondaId = $RondaId and rsu.UsuarioId = $UsuarioId;");
    }
    
    
    public function GetRondaSistemaServicioByUsuario_RondaFecha($UsuarioId, $RondaId, $Fecha) {
        return $this->db->objectBuilder()->rawQuery("select rsu.RondaServicioUsuarioId, s.Nombre, dr.DetalleRondaId, dr.Observacion1, dr.Observacion2, 
            dr.Fecha, dr.Estado, r.Nombre,r.Tipo,r.RondaId 
            from sistemas_rondaserviciousuario as rsu
        inner join sistemas_ronda as r on rsu.RondaId = r.RondaId
        inner join servicio as s on rsu.ServicioId = s.ServicioId
        left join sistemas_detalleronda as dr on rsu.RondaServicioUsuarioId = dr.RondaServicioUsuarioId and dr.Fecha='$Fecha'
        where rsu.RondaId = $RondaId and rsu.UsuarioId = $UsuarioId;");
    }
    
    
    public function GetRondaSistemaServicioByRondaSistemaServicioId($RondaSistemaServicioId) {
        $this->db->where("RondaSistemaServicioId", $RondaSistemaServicioId);
        return $this->db->objectBuilder()->getOne("Sistemas_RondaServicioUsuario");
    }
    
    public function UpdateRondaSistemaServicio($list, $id) {
        $this->db->where ('RondaServicioUsuarioId', $id);
        if ($this->db->update('Sistemas_RondaServicioUsuario', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function UpdateRonda($list, $id) {
        $this->db->where ('RondaId', $id);
        if ($this->db->update('Sistemas_Ronda', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function UpdateRondaSistema_Detalle($list, $id) {
        $this->db->where ('DetalleRondaId', $id);
        if ($this->db->update('sistemas_detalleronda', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function DeleteRonda($RondaId) {
        $this->db->query("Delete from sistemas_rondaserviciousuario where RondaId = $RondaId;");
    }
    
    public function DeleteRondaDetalle($RondaId, $Fecha) {
        $this->db->query("Delete from sistemas_detalleronda as dr
                inner join sistemas_rondaserviciousuario as rsu on dr.RondaServicioUsuarioId = dr.RondaServicioUsuarioId and rsu.RondaId = $RondaId
                where dr.Fecha = '$Fecha';");
    }
}
