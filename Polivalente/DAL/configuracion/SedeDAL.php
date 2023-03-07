<?php

/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";
class SedeDAL
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
        return $this->db->jsonBuilder()->get("sede");
    }
    public function getAllByUserId($UserId)
    {
        return $this->db->jsonBuilder()->rawQuery("SELECT DISTINCT se.SedeId, se.Nombre FROM serviciousuario as su
                STRAIGHT_JOIN servicio as s on su.ServicioId = s.ServicioId
                STRAIGHT_JOIN sede as se on s.SedeId = se.SedeId
                where su.UsuarioId = $UserId;");
    }

    public function getAllByUserIdRepuesto($UserId)
    {
        return $this->db->jsonBuilder()->rawQuery("SELECT DISTINCT se.SedeId, se.Nombre FROM serviciousuario as su
                STRAIGHT_JOIN servicio as s on su.ServicioId = s.ServicioId
                STRAIGHT_JOIN sede as se on s.SedeId = se.SedeId
                where su.UsuarioId = $UserId;");
    }

    public function GetAllByLiderUsuarioId($UserId)
    {
        return $this->db->jsonBuilder()->rawQuery("SELECT DISTINCT se.* FROM serviciousuario as su
                STRAIGHT_JOIN servicio as s on su.ServicioId = s.ServicioId
                STRAIGHT_JOIN sede as se on s.SedeId = se.SedeId
                where su.UsuarioId = $UserId;");
    }
    public function getAllByUserId_Biomedicos($UserId)
    {
        // USUARIO ID polivalente
        return $this->db->jsonBuilder()->rawQuery("SELECT DISTINCT se.SedeId, se.Nombre FROM biomedico.serviciousuario as su
                STRAIGHT_JOIN biomedico.servicio as s on su.ServicioId = s.ServicioId
                STRAIGHT_JOIN biomedico.sede as se on s.SedeId = se.SedeId
                STRAIGHT_JOIN biomedico.usuario as ub on ub.UsuarioId = su.UsuarioId
                inner join polivalente.usuario up on lower(up.NombreUsuario) = lower(ub.NombreUsuario)
                where up.UsuarioId = $UserId;");
    }

    public function CreateSede($list)
    {
        $ids = $this->db->insertMulti("sede", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateSedeUsuario($list)
    {
        $ids = $this->db->insertMulti("sedeusuario", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function GetSede($Nombre)
    {
        $this->db->where("Nombre", $Nombre);
        return $this->db->objectBuilder()->get("sede");
    }

    public function GetSedeByUsuario($SedeId, $UsuarioId)
    {
        $this->db->where("SedeId", $SedeId);
        $this->db->where("UsuarioId", $UsuarioId);
        return $this->db->objectBuilder()->get("sedeusuario");
    }

    public function GetSedeBySedeId($SedeId)
    {
        $this->db->where("SedeId", $SedeId);
        return $this->db->objectBuilder()->getOne("sede");
    }

    public function UpdateSede($list, $id)
    {
        $this->db->where('SedeId', $id);
        if ($this->db->update('sede', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
