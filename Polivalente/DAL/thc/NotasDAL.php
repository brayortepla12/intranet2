<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

class NotasDAL {

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

    public function GetNotasByHistoriaId($HistoriaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT n.NotasId, n.UsuarioId, n.HistoriaId, n.Observacion, 
        u.NombreCompleto, g.Nombre as Grupo, n.Fecha FROM polivalente.thc_notas as n
        INNER JOIN usuario as u on n.UsuarioId = u.UsuarioId
        INNER JOIN thc_grupo as g on n.GrupoId = g.GrupoId
        WHERE n.HistoriaId = $HistoriaId;");
    }

    public function CreateNota($list) {
        $ids = $this->db->insertMulti("thc_notas", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
}
