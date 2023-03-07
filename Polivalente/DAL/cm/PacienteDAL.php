<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";
require __DIR__ . "/../../vendor/autoload.php";

class PacienteDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();
        $dotenv->required(['HOST_SQLSERVER', 'USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'PORT_SQLSERVER']);
        $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
    }

    public function GetPacientesBySector($Sector) {
        $query = "SELECT HADM.NOADMISION , AFI.IDAFILIADO , AFI.PAPELLIDO, AFI.SAPELLIDO , AFI.PNOMBRE, AFI.SNOMBRE , HHAB.HABCAMA,  SECT.HABCAMA as Sector, SECT.DESCRIPCION, AFI.EDAD  
        FROM HADM INNER JOIN HHAB ON HADM.NOADMISION =HHAB.NOADMISION 
           INNER JOIN AFI ON HADM.IDAFILIADO=AFI.IDAFILIADO 
           INNER join HHAB sect on HHAB.SECTOR =sect.HABCAMA  and sect.CLASE ='SECTOR' 
        WHERE HHAB.ESTADOHAB='Ocupada' and HHAB.SECTOR = '$Sector' ORDER BY AFI.PNOMBRE";
        $result = $this->db->get_results($query);
        return $result;
    }

}


