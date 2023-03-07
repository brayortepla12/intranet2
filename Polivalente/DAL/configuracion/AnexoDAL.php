<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class AnexoDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function getAll() {
        return $this->db->objectBuilder()->get("PC_Anexo");
    }

    public function GetAllByVerificadorId($VerificadorId, $FlujoTrabajoId) {
        return $this->db->objectBuilder()->query("SELECT * FROM pc_anexo where FlujoTrabajoId = $FlujoTrabajoId and VerificadorId = $VerificadorId;");
    }

    public function DeleteByProtocoloId($ProcotoloId) {
        $this->db->query("Delete from PC_Anexo where ProtocoloId = " . $ProcotoloId);
    }
    
    public function GetUsuarioByFlujoTrabajoId($FlujoTrabajoId){
        return $this->db->objectBuilder()->query("SELECT u.Email, u.NombreCompleto, u.UsuarioId, s.Nombre as Sede, ser.Nombre as Servicio FROM usuario as u
        inner join pc_verificador as v on u.UsuarioId = v.UsuarioId
        inner join Sede as s on v.SedeId = s.SedeId
        inner join Servicio as ser on v.ServicioId = ser.ServicioId
        where v.FlujoTrabajoId = $FlujoTrabajoId and v.Estado = 'Activo';");
    }
    
    public function GetUsuarioByFirstFlujoTrabajo($ProtocoloId){
        return $this->db->objectBuilder()->query("SELECT u.Email, u.NombreCompleto, u.UsuarioId FROM usuario as u
        inner join pc_verificador as v on u.UsuarioId = v.UsuarioId
        inner join pc_flujotrabajo as ft on v.FlujoTrabajoId = ft.FlujoTrabajoId and ft.Orden = 0
        where ft.ProtocoloId = $ProtocoloId and v.Estado = 'Activo'");
    }

    public function CreateAnexo($list) {
        $ids = $this->db->insertMulti("PC_Anexo", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function GetAnexo($Nombre) {
        $this->db->where("Nombre", $Nombre);
        return $this->db->objectBuilder()->get("PC_Anexo");
    }

    public function UpdateAnexo($list, $id) {
        $this->db->where('AnexoId', $id);
        if ($this->db->update('PC_Anexo', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

}
