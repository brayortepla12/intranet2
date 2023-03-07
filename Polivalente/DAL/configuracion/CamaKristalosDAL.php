<?php

/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";require __DIR__ . "/../../vendor/autoload.php";

class CamaKristalosDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();
       $dotenv->safeLoad();
       $dotenv->required(['USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'HOST_SQLSERVER', 'PORT_SQLSERVER']);
       $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
    }
    
    public function GetCamas() {  
        $query = "SELECT TOP (1000) *
                    FROM [KCIELD].[dbo].[HHAB] where SECTOR = 'NEONATOSC' AND CLASE = 'Cama' AND (ESTADOHAB = 'Ocupada' or ESTADOHAB = 'Libre')";
        return $this->db->get_results($query);
    }
    
    public function IsValidAdmision($Admision) {  
        $query = "SELECT TOP (1000) *
                FROM [KCIELD].[dbo].[HHAB] where SECTOR = 'NEONATOSC' AND CLASE = 'Cama' AND ESTADOHAB = 'Ocupada' and NOADMISION IS NOT NULL and NOADMISION = '$Admision';";
        return $this->db->get_row($query);
    }
}
