<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class EnvioCorreoDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }


    public function getAll() {
        return $this->db->objectBuilder()->query("SELECT EnvioCorreoId,Estado,OrdenEnCurso, ProtocoloId, Archivos"
                . " from Autorizacion_EnvioCorreo where Estado <> 'Inactivo' "
                . " and Estado <> 'Finalizado';");
    }
    
    public function GetSiguiente($ProtocoloId, $Orden) {
        return $this->db->objectBuilder()->query("SELECT * FROM autorizacion_itemprotocolo where ProtocoloId= $ProtocoloId and Orden = $Orden and Estado = 'Activo';");
    }
    
    
    
    public function getAllByUserId($UserId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM almacen_relacioncosto as rc 
        inner join usuario as u on rc.UsuarioId = u.UsuarioId
        inner join servicio as ser on rc.ServicioId = ser.ServicioId
        where u.UsuarioId = $UserId;");
    }
    
    public function getToSendEmailByOrden($EnvioCorreoId,$Orden) {
        return $this->db->objectBuilder()->rawQuery("SELECT log.* FROM autorizacion_logemail as log
        inner join autorizacion_enviocorreo as ec on ec.EnvioCorreoId = log.EnvioCorreoId
        inner join autorizacion_itemprotocolo as ip on ec.ProtocoloId = ip.ProtocoloId
        where now() >= DATE_ADD(log.CreatedAt, INTERVAL ip.Tiempo MINUTE) and ip.Orden = $Orden and log.EnvioCorreoId = $EnvioCorreoId;");
    }
    
    public function CreateEnvioCorreo($list) {
        $ids = $this->db->insertMulti("Autorizacion_EnvioCorreo", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateItemProtocoloEnvioCorreo($list) {
        $ids = $this->db->insertMulti("EnvioCorreo_ItemProtocolo", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function UpdateEnvioCorreo($list, $id) {
        $this->db->where ('EnvioCorreoId', $id);
        if ($this->db->update('Almacen_EnvioCorreo', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
