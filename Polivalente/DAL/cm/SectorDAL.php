<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";require __DIR__ . "/../../vendor/autoload.php";

class SectorDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        $dotenv->required(['HOST_SQLSERVER', 'USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'PORT_SQLSERVER']);
        $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
    }

    public function GetSectores() {
        $query = "SELECT TOP (1000) HABCAMA as Habcama
            ,DESCRIPCION as Descripcion
            ,SECTOR as Sector
            FROM HHAB
            where CLASE ='SECTOR' ";
        $result = $this->db->get_results($query);
        return $result;
    }

}


