<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";require __DIR__ . "/../../vendor/autoload.php";

class FacturadoHoyDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();
        $dotenv->required(['USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'HOST_SQLSERVER', 'PORT_SQLSERVER']);
        $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
    }

    public function Prepare() {
        $query = "DECLARE @1FECHAINI DATETIME,@2FECHAFIN DATETIME;
        SELECT @1FECHAINI = CONVERT(VARCHAR, GETDATE(), 103)
		set @2FECHAFIN= @1FECHAINI
        SELECT CASE WHEN  MONTH(HADM.FECHAALTAMED) < MONTH(@1FECHAINI)
                       THEN DATENAME(MONTH, HADM.FECHAALTAMED)
                       ELSE DATENAME(MONTH,FTR.F_FACTURA)
                   END 'mes', 
                  CASE FTR.IDSEDE WHEN '01'  
                                  THEN  CASE WHEN LEFT(FTR.NOREFERENCIA,2)='01' 
                                             THEN 'CIELD' 
                                             ELSE 'CEMIC'
                                        END
                                  ELSE 'CSI'  
                  END sede,
                          convert(VARCHAR(50) ,CAST(SUM(VR_TOTAL) AS MONEY),1 ) 'valor'
        FROM FTR INNER JOIN HADM ON FTR.NOREFERENCIA = HADM.NOADMISION AND FTR.PROCEDENCIA in ('SALUD','CE', 'CI')
        WHERE CONVERT (VARCHAR, F_FACTURA, 103) >= @1FECHAINI 
          AND CONVERT(VARCHAR, F_FACTURA, 103) <= @2FECHAFIN
            AND FTR.ESTADO='P' 
            GROUP BY CASE WHEN  MONTH(HADM.FECHAALTAMED) < MONTH(@1FECHAINI)
                       THEN DATENAME(MONTH, HADM.FECHAALTAMED)
                       ELSE DATENAME(MONTH,FTR.F_FACTURA)
                   END, 
            CASE FTR.IDSEDE WHEN '01'  
                                  THEN  CASE WHEN LEFT(FTR.NOREFERENCIA,2)='01' 
                                             THEN 'CIELD'  
                                             ELSE 'CEMIC'
                                        END
                                  ELSE 'CSI'  
                  END
        union all
                SELECT 
                CASE WHEN  MONTH(HADM.FECHAALTAMED) < MONTH(@1FECHAINI)
                       THEN DATENAME(MONTH, HADM.FECHAALTAMED) + '=> Total'
                       ELSE DATENAME(MONTH,FTR.F_FACTURA) + '=> Total'
                   END 'mes','' sede,
                  convert(VARCHAR(50) ,CAST(SUM(VR_TOTAL) AS MONEY),1 ) 'valor'
        FROM FTR INNER JOIN HADM ON FTR.NOREFERENCIA = HADM.NOADMISION AND FTR.PROCEDENCIA in ('SALUD','CE', 'CI')
        WHERE CONVERT (VARCHAR, F_FACTURA, 103) >= @1FECHAINI 
        AND CONVERT(VARCHAR, F_FACTURA, 103)<= @2FECHAFIN
           AND FTR.ESTADO='P' 
            GROUP BY CASE WHEN  MONTH(HADM.FECHAALTAMED) < MONTH(@1FECHAINI)
                       THEN DATENAME(MONTH, HADM.FECHAALTAMED) + '=> Total'
                       ELSE DATENAME(MONTH,FTR.F_FACTURA) + '=> Total'
                   END 
        order by 1;";
        return $this->db->get_results($query);
    }

    public function GetFacturadoHoy() {
        $query = "DECLARE @1FECHAINI DATETIME,@2FECHAFIN DATETIME
        SELECT @1FECHAINI = CONVERT(VARCHAR, GETDATE(), 103),@2FECHAFIN=CONVERT(VARCHAR, GETDATE(), 103)


        SELECT 'Total Facturado Hoy' mes, '' sede,
            convert(VARCHAR(50) ,CAST(SUM(VR_TOTAL) AS MONEY),1 ) 'valor'
        FROM FTR INNER JOIN HADM ON FTR.NOREFERENCIA = HADM.NOADMISION AND FTR.PROCEDENCIA in ('SALUD','CE')
        WHERE CONVERT (VARCHAR, F_FACTURA, 103) >= @1FECHAINI 
        AND CONVERT(VARCHAR, F_FACTURA, 103)<= @2FECHAFIN
           AND FTR.ESTADO='P' 
        order by 1";

        $result = $this->db->get_results($query);
        return $result;
    }

    
}
