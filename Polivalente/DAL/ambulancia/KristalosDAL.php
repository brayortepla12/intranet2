<?php

/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";require __DIR__ . "/../../vendor/autoload.php";

class KristalosDAL {
    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();
       $dotenv->required(['HOST_SQLSERVER', 'USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'PORT_SQLSERVER']);
       $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
    }
    
    
    public function GetTotalFacturado($Admision) {
//        $query = "SELECT * FROM tripulacion where tipoTripulacion = 'CONDUCTOR' ORDER BY nombre;";//        
        $query = "SELECT HADM.NOADMISION , SUM(HPRED.CANTIDAD *  HPRED.VALOR) VALORCARGADO 
                    FROM HADM INNER JOIN HPRE ON HADM.NOADMISION =HPRE.NOADMISION
                      INNER JOIN HPRED ON HPRE.NOPRESTACION=HPRED.NOPRESTACION 
                    WHERE 
                    HADM.NOADMISION = '" . $Admision . "'and
                    COALESCE(HPRED.NOCOBRABLE ,0)=0
                    AND COALESCE(ENCARGOS ,0)=0
                    group by  HADM.NOADMISION";

        return $this->db->get_row($query);
//        return $this->db->GetCondutores($query);
    }
    
    public function GetAdmision($Admision) {
        $query = "SELECT HADM.NOADMISION
                    FROM HADM
                    WHERE 
                    HADM.NOADMISION = '" . $Admision ."'";
        return $this->db->get_row($query);
    }
}
