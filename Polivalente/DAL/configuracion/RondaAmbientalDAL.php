<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class RondaAmbientalDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    public function getAllFormularios() {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM formato where Estado = 'Activo';");
    }
    public function getAllFormulariosByServicioId($ServicioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT distinct fs.ServicioId, f.* FROM formato as f
            inner join formatoservicio as fs on f.FormatoId = fs.FormatoId
            where fs.ServicioId = $ServicioId and f.Estado = 'Activo';");
    }
    public function getRondaAmbientalByServicioId($ServicioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM rondaambiental as ra
        inner join Servicio as s on ra.ServicioId = s.ServicioId
        inner join Formato as f on ra.FormatoId = f.FormatoId
        where ra.ServicioId = $ServicioId and ra.Estado = 'Activo';");
    }
    public function getItemsByFormulario($FormatoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM itemformato where FormatoId = $FormatoId order by itemformatoid, itemformatoid * 1;");
    }
    
    public function getItemsByFormularioCompleto($RondaAmbientalId) {
        return $this->db->objectBuilder()->rawQuery("SELECT i.CreatedAt,i.CreatedBy,i.Descripcion,i.Estado,i.FormatoId,i.Identificador,
        i.ItemFormatoId,i.ModifiedAt,i.ModifiedBy,i.Pe,d.Observacion,d.Po,d.DetalleId FROM itemformato as i
        inner join Detalle as d on i.ItemFormatoId = d.ItemFormatoId
        where d.RondaAmbientalId = $RondaAmbientalId order by i.Identificador");
    }
    
    public function getAllBySede($SedeId) {
        $this->db->where("SedeId", $SedeId);
        return $this->db->jsonBuilder()->get("RondaAmbiental");
    }
   
    public function CreateRondaAmbiental($list) {
        $ids = $this->db->insertMulti("RondaAmbiental", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateDetalleRondaAmbiental($list) {
        $ids = $this->db->insertMulti("Detalle", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function UpdateRondaAmbiental($list, $id) {
        $this->db->where ('RondaAmbientalId', $id);
        if ($this->db->update('RondaAmbiental', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    public function UpdateDetalleRondaAmbiental($list, $id) {
        $this->db->where ('DetalleId', $id);
        if ($this->db->update('Detalle', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
