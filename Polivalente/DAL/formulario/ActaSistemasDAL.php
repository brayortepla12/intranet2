<?php

include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

class ActaSistemasDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function GetDetalleByActaId($ActaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM sistemas_detalleacta where ActaId = $ActaId;");
    }

    public function GetAll() {
        return $this->db->objectBuilder()->rawQuery("SELECT a.*, ifnull(s.Nombre, a.Area) as Servicio, ifnull(sed.Nombre, '') as Sede,
		ifnull(u.NombreCompleto, a.RecibeN) as Recibe,
        ifnull(u.Cargo, a.RecibeC) as RecibeCargo,
        u2.NombreCompleto as Responsable,
        u2.Cargo as ResponsableCargo
        FROM sistemas_acta as a 
        left join Servicio as s on a.ServicioId = s.ServicioId
        left join Sede as sed on s.SedeId = sed.SedeId
        left join Usuario as u on u.UsuarioId = a.RecibeId
        STRAIGHT_JOIN Usuario as u2 on u2.UsuarioId = a.ResponsableId
        order by ActaId DESC;");
    }
    
    public function GetConsecurivoActual($TipoActa) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM sistemas_consecutivo where TipoActa = '$TipoActa';");
    }

    public function CreateDetalleActaSistemas($list) {
        $ids = $this->db->insertMulti("sistemas_detalleacta", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateActaSistemas($list) {
        $ids = $this->db->insertMulti("sistemas_Acta", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    
    public function UpdateConsecutivo($list, $id) {
        $this->db->where ('ConsecutivoId', $id);
        if ($this->db->update('sistemas_consecutivo', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

}
