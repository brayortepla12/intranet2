<?php
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class DetallesAmbulanciaDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function DeleteDetalleReporteByReporteId($ReporteId) {
        return $this->db->objectBuilder()->rawQuery("DELETE FROM ambulancia_detallereporte where ReporteId = $ReporteId;");
    }
    
    public function GetDetallesByReporteId($ReporteAmbulanciaId) {
        return $this->db->objectBuilder()->rawQuery("select * from ambulancia_detalle as d
        inner join ambulancia_detallereporte as dr on d.DetalleId = dr.DetalleId where dr.ReporteId = $ReporteAmbulanciaId");
    }
    
    public function GetAll() {
        return $this->db->objectBuilder()->rawQuery("select * from ambulancia_detalle order by Descripcion");
    }
    
    public function CreateDetalleReporte($list) {
        $ids = $this->db->insertMulti("ambulancia_detallereporte", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
}
