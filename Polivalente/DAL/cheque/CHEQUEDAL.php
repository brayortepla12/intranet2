<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";require __DIR__ . "/../../vendor/autoload.php";

class CHEQUEDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        $dotenv->required(['HOST_SQLSERVER', 'USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'PORT_SQLSERVER']);
        $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
    }
    
    public function GetCHEQUE($Numero) {
        $query = "SELECT T.DESCRIPCION, T.CTA_BCO,T.IDTERCERO,T.FECHA,T.VALOR,T.NOMBRECLIENTE,T.OBSERVACION
        FROM
        (SELECT * from dbo.FNK_DATOCHEQUE('$Numero')) AS T;";
        $result = $this->db->get_results($query);
        return $result;
    }
}
