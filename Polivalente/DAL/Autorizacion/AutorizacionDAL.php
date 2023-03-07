<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";
class AutorizacionDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
       $dotenv->safeLoad();
       $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
       $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }


    public function GetAutorizaciones($Dia, $Mes, $Year) {
        return $this->db->objectBuilder()->query("SELECT a.*, u.nombre FROM autorizaciones.autorizaciones a
        inner join autorizaciones.usuarios as u on a.usuario = u.usuario
        where (a.dia = '$Dia' or '$Dia' = 'TODOS') and a.mes = '$Mes' and a.anio = $Year order by a.idautorizacion DESC;");
    }
    
    public function GetAllByPlantilla($ServicioId, $UserId) {
        return $this->db->objectBuilder()->query("SELECT a.*, rc.RelacionCostoId , rc.DiasConsumo, rc.Cantidad from almacen_Autorizacion as a 
        inner join almacen_relacioncosto as rc on a.AutorizacionId = rc.AutorizacionId
        where rc.ServicioId = $ServicioId and rc.UsuarioId = $UserId and rc.Estado <> 'Inactivo' order by Nombre;");
    }
    
    public function GetItemProtocoloById($ProtocoloId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM Autorizacion_ItemProtocolo where ProtocoloId=$ProtocoloId");
    }
    
    public function CreateProtocoloAutorizacion($list) {
        $ids = $this->db->insertMulti("Autorizacion_Protocolo", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateItemProtocoloAutorizacion($list) {
        $ids = $this->db->insertMulti("Autorizacion_ItemProtocolo", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function UpdateAutorizacion($list, $id) {
        $this->db->where ('AutorizacionId', $id);
        if ($this->db->update('Almacen_Autorizacion', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
