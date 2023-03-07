<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";
require __DIR__ . "/../../vendor/autoload.php";

class AcostadosDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();$dotenv->required(['USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'HOST_SQLSERVER', 'PORT_SQLSERVER']);
        $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
        
    }
    
    public function GetAcostados() {
        $query = "----------------
            --PACIENTES ACOSTADO
            -----------------
            SELECT 
		CASE HHAB.IDSEDE WHEN '01' THEN 'CLD' 
                                     WHEN '02' THEN 'CEMIC'
                                     WHEN '03' THEN 'CSI'  END sede, 
        convert(VARCHAR(50) ,CAST(SUM(X.TOTAL) AS MONEY),1 ) total 
			FROM HADM 
					INNER JOIN HHAB ON HADM.NOADMISION = HHAB.NOADMISION
						INNER JOIN (SELECT HPRE.NOADMISION, SUM(HPRED.VALOREXCEDENTE)TOTAL
									FROM HPRE 
										INNER JOIN HPRED ON HPRE.NOPRESTACION = HPRED.NOPRESTACION
										GROUP BY HPRE.NOADMISION
									) X
                          ON HADM.NOADMISION = X.NOADMISION
			GROUP BY HHAB.IDSEDE";

        $result = $this->db->get_results($query);
        return $result;
    }
    public function GetTotalAcostados() {
        $query = "--------------------------
                --TOTAL PACIENTES ACOSTADOS
                -----------------------
                SELECT 
             'TOTAL' total,
                         convert(VARCHAR(50) ,CAST(SUM(X.TOTAL) AS MONEY),1 )	total1 
                                        FROM HADM 
                                                INNER JOIN HHAB ON HADM.NOADMISION = HHAB.NOADMISION
                                                INNER JOIN (SELECT HPRE.NOADMISION, SUM(HPRED.VALOREXCEDENTE)TOTAL
                                                                                FROM HPRE 
                                                                                INNER JOIN HPRED ON HPRE.NOPRESTACION = HPRED.NOPRESTACION
                                                                                GROUP BY HPRE.NOADMISION
                                                                        ) X
                    ON HADM.NOADMISION = X.NOADMISION";

        $result = $this->db->get_results($query);
        return $result;
    }

}
