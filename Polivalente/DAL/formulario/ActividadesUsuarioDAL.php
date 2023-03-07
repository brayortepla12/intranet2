<?php

include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class ActividadesUsuarioDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateActividadesUsuario($list) {
        $ids = $this->db->insertMulti("ActividadesUsuario", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function GetActividadesPendientes($UsuarioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT ar.ActividadesRondaId, au.Estado, ar.Descripcion, ar.Equipo, ar.Tipo, au.Observacion, 
            au.CreatedBy, au.ActividadesUsuarioId
            FROM polivalente.actividadesusuario as au
        STRAIGHT_JOIN actividadesronda as ar on au.ActividadesRondaId = ar.ActividadesRondaId
        where au.UsuarioId = $UsuarioId and (isnull(au.Cumplimiento) || au.Cumplimiento = 'NO');");
    }

    public function UpdateActividadesUsuario($list, $id) {
        $this->db->where('ActividadesUsuarioId', $id);
        if ($this->db->update('ActividadesUsuario', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function GetActividadesUsuarioByActividadesRondaId($ActividadesRondaId) {
        return $this->db->objectBuilder()->rawQuery("select u.NombreCompleto, au.Cumplimiento,au.Observacion, au.ActividadesUsuarioId, au.Estado
            from actividadesusuario as au
        STRAIGHT_JOIN usuario as u on au.UsuarioId = u.UsuarioId
        where au.ActividadesRondaId = $ActividadesRondaId;");
    }

    public function GetActividadesUsuarioByRondaId($RondaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * from ActividadesUsuario
            where RondaId = $RondaId
               order by ActividadesUsuarioId desc;");
    }

    public function DeleteRondaUsuarioByActividadRondaId($ActividadRondaId) {
        $this->db->rawQuery("DELETE FROM ActividadesUsuario where ActividadesRondaId = $ActividadRondaId");
    }

    public function GetAllActividadesUsuarios() {
        return $this->db->jsonBuilder()->rawQuery("SELECT * from ActividadesUsuario
               order by ActividadesUsuarioId desc;");
    }

    public function GetAllActividadUsuarioById($ActividadesUsuarioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT ar.ActividadesRondaId, ar.Descripcion, ar.Equipo, ar.Tipo, 
            au.Observacion, au.CreatedBy, au.ActividadesUsuarioId, au.Estado
            FROM polivalente.actividadesusuario as au
        STRAIGHT_JOIN actividadesronda as ar on au.ActividadesRondaId = ar.ActividadesRondaId
        where au.ActividadesUsuarioId= $ActividadesUsuarioId;");
    }

    public function CountActividadesUsuarioes($SedeId) {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total FROM solicitud where Estado <> 'Completado' and Estado <> 'Cancelado' and SedeId=$SedeId;");
    }

}
