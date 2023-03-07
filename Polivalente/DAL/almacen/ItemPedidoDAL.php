<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class ItemPedidoDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }


    public function getAll() {
        return $this->db->objectBuilder()->query("SELECT * from almacen_itempedido where Estado <> 'Inactivo' order by ItemPedidoId desc;");
    }
    
    public function getAllBySedeId($SedeId) { //pa.CargoSolicitante, pa.FechaSolicitud, pa.CreatedAt, pa.CreatedBy, pa.Estado, pa.SolicitanteId, pa.NombreSolicitante, pa.ItemPedidoId, 
        return $this->db->objectBuilder()->rawQuery("SELECT pa.CargoSolicitante, pa.FechaSolicitud, pa.CreatedAt, pa.CreatedBy, pa.Estado, pa.SolicitanteId, pa.NombreSolicitante, pa.ItemPedidoId, 
            s.Nombre as Servicio, se.Nombre as Sede FROM ItemPedido as pa
                STRAIGHT_JOIN servicio as s on pa.ServicioId = s.ServicioId
                STRAIGHT_JOIN sede as se on pa.SedeId = se.SedeId
                where s.SedeId = $SedeId and  pa.Estado <> 'Inactivo' order by ItemPedidoId desc;");
    }
    
    public function getAllByUserId($UserId) {
        return $this->db->objectBuilder()->rawQuery("SELECT pa.*,
            s.Nombre as Servicio, se.Nombre as Sede FROM ItemPedido as pa
            STRAIGHT_JOIN servicio as s on pa.ServicioId = s.ServicioId
            STRAIGHT_JOIN sede as se on pa.SedeId = se.SedeId
            where SolicitanteId = $UserId and Estado <> 'Inactivo' order by ItemPedidoId desc;");
    }
    
    public function getAllById($ItemPedidoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT pa.*,
            s.Nombre as Servicio, se.Nombre as Sede FROM ItemPedido as pa
                STRAIGHT_JOIN servicio as s on pa.ServicioId = s.ServicioId
                STRAIGHT_JOIN sede as se on pa.SedeId = se.SedeId
                where pa.ItemPedidoId = $ItemPedidoId and Estado <> 'Inactivo' order by ItemPedidoId desc;");
    }
    
    public function CreateItemPedido($list) {
        $ids = $this->db->insertMulti("almacen_itempedido", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateItemPedidoRepuesto($list)
    {
        $ids = $this->db->insertMulti("almacen_itempedido", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
   
    public function UpdateItemPedido($list, $id) {
        $this->db->where ('ItemPedidoId', $id);
        if ($this->db->update('almacen_itempedido', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
