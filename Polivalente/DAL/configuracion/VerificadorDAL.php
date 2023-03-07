<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class VerificadorDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function getAll() {
        return $this->db->objectBuilder()->get("PC_Verificador");
    }

    public function getAllByFlujoTrabajoId($FlujoTrabajoId) {
        return $this->db->objectBuilder()->query("SELECT pv.*, s.Nombre as Sede, ser.Nombre as Servicio, u.NombreCompleto FROM pc_verificador as pv
        STRAIGHT_JOIN Usuario as u on u.UsuarioId = pv.UsuarioId
        STRAIGHT_JOIN Sede as s on s.SedeId = pv.SedeId
        STRAIGHT_JOIN servicio as ser on ser.ServicioId = pv.ServicioId
        where pv.FlujoTrabajoId = $FlujoTrabajoId and pv.Estado = 'Activo';");
    }

    public function DeleteByProtocoloId($ProcotoloId) {
        $this->db->query("Delete from PC_Verificador where ProtocoloId = " . $ProcotoloId);
    }
    
    public function GetUsuarioByFlujoTrabajoId($FlujoTrabajoId){
        return $this->db->objectBuilder()->query("SELECT v.VerificadorId, u.Email, CONCAT(p.PrimerNombre, ' ', p.PrimerApellido) NombreCompleto, 
        u.UsuarioId, s.Nombre as Sede, ser.Nombre as Servicio 
        FROM usuario as u
        LEFT JOIN ct_persona as p on p.UsuarioIntranetId = u.UsuarioId
        STRAIGHT_JOIN pc_verificador as v on u.UsuarioId = v.UsuarioId
        STRAIGHT_JOIN Sede as s on v.SedeId = s.SedeId
        STRAIGHT_JOIN Servicio as ser on v.ServicioId = ser.ServicioId
        where v.FlujoTrabajoId = $FlujoTrabajoId and v.Estado = 'Activo';");
    }
    
    public function GetUsuarioByFirstFlujoTrabajo($ProtocoloId){
        return $this->db->objectBuilder()->query("SELECT u.Email, CONCAT(p.PrimerNombre, ' ', p.PrimerApellido) NombreCompleto, u.UsuarioId FROM usuario as u
        LEFT JOIN ct_persona as p on p.UsuarioIntranetId = u.UsuarioId
        STRAIGHT_JOIN pc_verificador as v on u.UsuarioId = v.UsuarioId
        STRAIGHT_JOIN pc_flujotrabajo as ft on v.FlujoTrabajoId = ft.FlujoTrabajoId and ft.Orden = 0
        where ft.ProtocoloId = $ProtocoloId and v.Estado = 'Activo'");
    }

    public function CreateVerificador($list) {
        $ids = $this->db->insertMulti("PC_Verificador", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function GetVerificador($Nombre) {
        $this->db->where("Nombre", $Nombre);
        return $this->db->objectBuilder()->get("PC_Verificador");
    }

    public function UpdateVerificador($list, $id) {
        $this->db->where('VerificadorId', $id);
        if ($this->db->update('PC_Verificador', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

}
