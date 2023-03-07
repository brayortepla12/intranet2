<?php
/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";
class DetalleCronogramaSistemaDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function DeleteDetalleCronogramaSinReportes($CronogramaId) {
        return $this->db->objectBuilder()->query("DELETE dc FROM sistemas_detallecronograma as dc where dc.CronogramaId = $CronogramaId and 0 =
        (select count(*) from sistemas_reportedcronograma as rdc where rdc.DetalleCronogramaId = dc.DetalleCronogramaId);");
    }
    
    
    public function CreateDetalleCronogramaSistema($list) {
        $ids = $this->db->insertMulti("sistemas_DetalleCronograma", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
}
