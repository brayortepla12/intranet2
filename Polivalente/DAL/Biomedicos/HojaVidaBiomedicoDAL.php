<?php

include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

/**
 * Description of HojaVidaBiomedicoDAL
 *
 * @author DESARROLLO2
 */
class HojaVidaBiomedicoDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }

    public function __destruct() {
        $this->db->disconnect();
    }
    
    public function GetHojaVidaByServicioBIOQR($ServicioId) {
        return $this->db->jsonBuilder()->rawQuery("select h.HojaVidaId, h.Ubicacion, h.Equipo, h.Serie, h.Marca, h.Inventario, h.Serie, h.Modelo, h.Foto 
        FROM biomedico.hojavida as h 
        where h.ServicioId = $ServicioId
        order by h.Equipo;");
    }

}
