<?php

/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";
class ServicioDAL
{
    private $db;

    public function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
        $this->db->disconnect();
    }
    public function getAll()
    {
        return $this->db->jsonBuilder()->get("Servicio");
    }

    public function GetServicioByPersonaId($UsuarioId, $ServicioId)
    {
        $this->db->where("UsuarioId", $UsuarioId);
        $this->db->where("ServicioId", $ServicioId);
        return $this->db->objectBuilder()->get("serviciousuario");
    }
    public function getAllBySede($SedeId)
    {
        $this->db->where("SedeId", $SedeId);
        $this->db->orderBy("Nombre", "ASC");
        return $this->db->jsonBuilder()->get("servicio");
    }
    public function getAllBySedeWithEquipo($SedeId, $Tablas)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT s.ServicioId, s.Nombre FROM $Tablas->servicio as s
            where s.SedeId = $SedeId and (select count(h.HojaVidaId) From $Tablas->hojavida as h where s.ServicioId = h.ServicioId) > 0 order by s.Nombre;");
    }
    public function getAllBySedeBIOMEDICOS($SedeId, $UsuarioId)
    {
        return $this->db->jsonBuilder()->rawQuery("SELECT s.ServicioId, s.Nombre FROM biomedico.servicio as s
            STRAIGHT_JOIN biomedico.serviciousuario as su on s.ServicioId = su.ServicioId
            STRAIGHT_JOIN usuario as up on up.UsuarioId = $UsuarioId
            inner join biomedico.usuario as bu on bu.NombreUsuario =  up.NombreUsuario
            where s.SedeId = $SedeId and su.UsuarioId = bu.UsuarioId order by s.Nombre;");
    }

    public function GetAllBySedeAndUserId($SedeId, $UserId)
    {
        return $this->db->jsonBuilder()->rawQuery("SELECT s.ServicioId, s.IsVisible, s.SedeId,se.Nombre as NombreSede, s.Nombre FROM servicio as s
            STRAIGHT_JOIN serviciousuario as su on s.ServicioId = su.ServicioId
            STRAIGHT_JOIN sede as se on s.SedeId = se.SedeId
            where su.UsuarioId = $UserId and s.SedeId = $SedeId order by s.Nombre;");
    }
    public function getServicioUsuario($ServicioId, $UsuarioId)
    {
        $this->db->where("ServicioId", $ServicioId);
        $this->db->where("UsuarioId", $UsuarioId);
        return $this->db->objectBuilder()->getOne("serviciousuario");
    }
    public function getFormatoServicio($ServicioId, $FormatoId)
    {
        $this->db->where("ServicioId", $ServicioId);
        $this->db->where("FormatoId", $FormatoId);
        return $this->db->objectBuilder()->getOne("formatoservicio");
    }
    public function GetAllByLiderUsuarioId($UserId)
    {
        return $this->db->jsonBuilder()->rawQuery("SELECT s.ServicioId, s.IsVisible, s.SedeId,se.Nombre as NombreSede, s.Nombre FROM servicio as s
            STRAIGHT_JOIN serviciousuario as su on s.ServicioId = su.ServicioId
            STRAIGHT_JOIN sede as se on s.SedeId = se.SedeId
            where su.UsuarioId = $UserId order by s.Nombre;");
    }
    public function getAllByUserId($UserId)
    {
        return $this->db->jsonBuilder()->rawQuery("SELECT s.ServicioId, s.IsVisible, s.SedeId,se.Nombre as NombreSede, s.Nombre FROM servicio as s
            STRAIGHT_JOIN serviciousuario as su on s.ServicioId = su.ServicioId
            STRAIGHT_JOIN sede as se on s.SedeId = se.SedeId
            where su.UsuarioId = $UserId order by s.Nombre;");
    }

    public function getAllByUserIdRepuesto($UserId)
    {
        return $this->db->jsonBuilder()->rawQuery("SELECT s.ServicioId, s.IsVisible, s.SedeId,se.Nombre as NombreSede, s.Nombre FROM servicio as s
            STRAIGHT_JOIN serviciousuario as su on s.ServicioId = su.ServicioId
            STRAIGHT_JOIN sede as se on s.SedeId = se.SedeId
            where su.UsuarioId = $UserId order by s.Nombre;");
    }
    public function getAllByFormatoId($FormatoId)
    {
        return $this->db->jsonBuilder()->rawQuery("SELECT s.ServicioId, s.SedeId,se.Nombre as NombreSede, s.Nombre FROM servicio as s
            STRAIGHT_JOIN formatoservicio as fs on s.ServicioId = fs.ServicioId
            STRAIGHT_JOIN sede as se on s.SedeId = se.SedeId
            where fs.FormatoId = $FormatoId;");
    }
    public function CreateServicio($list)
    {
        $ids = $this->db->insertMulti("Servicio", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function RemoverServicioUsuario($ServicioId, $UsuarioId)
    {
        $this->db->query("Delete from ServicioUsuario where ServicioId=$ServicioId and UsuarioId=$UsuarioId");
    }
    public function RemoverFormatoServicio($ServicioId, $FormatoId)
    {
        $this->db->query("Delete from FormatoServicio where ServicioId=$ServicioId and FormatoId=$FormatoId");
    }
    public function CreateServicioUsuario($list)
    {
        $ids = $this->db->insertMulti("ServicioUsuario", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function CreateFormatoServicio($list)
    {
        $ids = $this->db->insertMulti("formatoservicio", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function GetServicio($Nombre)
    {
        $this->db->where("Nombre", $Nombre);
        return $this->db->objectBuilder()->get("servicio");
    }
    public function GetServicioBYID($ServicioId)
    {
        return $this->db->objectBuilder()->rawQueryOne("
            SELECT ser.Nombre as Servicio, ser.ServicioId, s.SedeId, s.Nombre as Sede FROM servicio as ser 
            STRAIGHT_JOIN sede as s on ser.SedeId = s.SedeId 
            where ser.ServicioId = $ServicioId;");
    }
    private function TransferirHojasANuevaSede($ServicioId)
    {
        $this->db->query("SET SQL_SAFE_UPDATES=0;");
        $this->db->query("
                UPDATE HojaVida as h INNER JOIN 
                       servicio as s
                       ON h.ServicioId = s.ServicioId
                    SET h.SedeId = s.SedeId, h.ServicioId = s.ServicioId 
                    where s.ServicioId = $ServicioId;
                ");
        $this->db->query("
                UPDATE Reporte as h INNER JOIN 
                       servicio as s
                       ON h.ServicioId = s.ServicioId
                    SET h.SedeId = s.SedeId, h.ServicioId = s.ServicioId where s.ServicioId = $ServicioId;
                ");
        $this->db->query("SET SQL_SAFE_UPDATES=1;");
    }
    public function UpdateServicio($list, $id)
    {
        $this->db->where('ServicioId', $id);
        if ($this->db->update('servicio', $list[0])) {
            $this->TransferirHojasANuevaSede($id);
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
