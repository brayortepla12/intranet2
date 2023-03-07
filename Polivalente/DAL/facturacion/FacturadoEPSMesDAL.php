<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";require __DIR__ . "/../../vendor/autoload.php";

class FacturadoEPSMesDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'HOST_SQLSERVER', 'PORT_SQLSERVER']);
        $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
    }

    public function GetFacturadoEPSMes($From, $To) {
        $query = "DECLARE @1FECHAINI DATETIME,@2FECHAFIN DATETIME
        SELECT @1FECHAINI=CONVERT(VARCHAR, '$From', 103),@2FECHAFIN = CONVERT(VARCHAR, '$To', 103)

        SELECT top 100 TER.RAZONSOCIAL, 

        REPLACE(convert(VARCHAR(50) ,CAST(SUM(VR_TOTAL) AS MONEY),1 ),'.00','') VALOR

          FROM FTR 
          --LEFT OUTER JOIN HADM ON FTR.NOREFERENCIA=HADM.NOADMISION AND FTR.PROCEDENCIA in ('SALUD','CE')
          left join TER ON FTR.IDTERCERO=TER.IDTERCERO
          WHERE F_FACTURA>=@1FECHAINI 
          AND convert(varchar(10),F_FACTURA,112)<=@2FECHAFIN
          AND FTR.ESTADO='P' and FTR.IDPLAN NOT IN ('PART' , 'PARISS', 'PARTJU', 'EMPL' ,  'EMPISS', 'PARTN')
          GROUP BY TER.RAZONSOCIAL
          ORDER BY SUM(VR_TOTAL) DESC";

        $result = $this->db->get_results($query);
        return $result;
    }

    
}
