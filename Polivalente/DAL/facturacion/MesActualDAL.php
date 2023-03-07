<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";require __DIR__ . "/../../vendor/autoload.php";

class MesActualDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();
        $dotenv->required(['USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'HOST_SQLSERVER', 'PORT_SQLSERVER']);
        $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
    }

    public function Prepare($From, $To) {
        $query = "DECLARE @1FECHAINI DATETIME,@2FECHAFIN DATETIME
        SELECT @1FECHAINI=CONVERT(VARCHAR, '$From', 103),@2FECHAFIN = CONVERT(VARCHAR, '$To', 103)

        INSERT INTO CM_FacturadoJaime (Mes,Sede, Valor)  
        SELECT MES , SEDECCOSTO SEDE , SUM(VALOR) VR_TOTAL
        FROM (
        SELECT  CASE WHEN  HADM.FECHAALTAMED < @1FECHAINI
        --SELECT  CASE WHEN  HADM.FECHAALTAMED < @1FECHAINI
                       THEN DATENAME(MONTH, HADM.FECHAALTAMED)
                       ELSE DATENAME(MONTH,FTR.F_FACTURA)
                   END 'mes', 
          CASE FTR.IDSEDE WHEN '01'  
                          THEN CASE WHEN LEFT(FTR.NOREFERENCIA,2)='01' 
                                    THEN 'CLD' 
                                    ELSE 'CEMIC' 
                               END 
                          ELSE 'CSI' 
           END SEDE,
              CASE ROW_NUMBER() OVER(PARTITION BY FTRD.N_FACTURA ORDER BY FTRD.N_FACTURA) WHEN 1 THEN COALESCE(FTR.VR_TOTAL, 0)  ELSE 0 END 

         VALOR, FTRD.VR_TOTAL TOTALDETALLE,
          CASE CEN.IDSEDE 
                WHEN '01'  THEN 'CLD'  
                WHEN '02' THEN 'CEMIC'  
                WHEN '04' THEN 'CEMIC'  
                ELSE 'CSI'  END  SEDECCOSTO

          FROM FTR left outer JOIN HADM ON FTR.NOREFERENCIA=HADM.NOADMISION AND FTR.PROCEDENCIA='SALUD' 
          LEFT OUTER JOIN FTRD ON FTR.N_FACTURA =FTRD.N_FACTURA 
          LEFT OUTER JOIN CEN ON FTRD.CCOSTO=CEN.CCOSTO 
          --,'financiero', 'ci', 'ce'
          WHERE F_FACTURA>=@1FECHAINI 
          --AND convert(varchar(10),F_FACTURA,112)<=@2FECHAFIN
          AND F_FACTURA<=@2FECHAFIN
          AND FTR.ESTADO='P' 
          )FACTURACION
            GROUP BY MES , SEDECCOSTO
          order by 1";
        $this->db->query($query);
    }

    public function GetMesActual($mes) {
        $query = "---------------------
                    -- MES ACTUAL
                    ---------------------
                        select 
                      Mes, 
                      Sede,
                      convert(VARCHAR(50) ,CAST(SUM(Valor) AS MONEY),1 ) Valor
                      FROM CM_FacturadoJaime
                      WHERE MES='$mes'
                           GROUP BY SEDE,MES
                      order by 1 desc,2 desc   ";

        $result = $this->db->get_results($query);
        return $result;
    }

    public function GetTotalMesActual($mes) {
        $query = "----------------------
            -- TOTAL MES ACTUAL
            ----------------------
              select 
               '......' Mes,
               'TOTAL' Sede,
               convert(VARCHAR(50) ,CAST(SUM(Valor) AS MONEY),1 ) Valor
              FROM CM_FacturadoJaime
              WHERE MES='$mes'
                    order by 1 desc,2 desc";

        $result = $this->db->get_results($query);
        return $result;
    }

    public function GetMesRefactura($mes) {
        $query = "---------------------
    -- MES refactura
    ---------------------
      select 
      Mes,
     -- Sede,
      convert(VARCHAR(50) ,CAST(SUM(Valor) AS MONEY),1 ) Valor
      FROM CM_FacturadoJaime
      WHERE MES<>'$mes' 
           GROUP BY mes
      order by 1 desc ";

        $result = $this->db->get_results($query);
        return $result;
    }

    public function GetTotalMesRefactura($mes) {
        $query = "----------------------
        -- sum TOTAL MES refacturado
        ----------------------
            select 
        '.....' Mes,
          'TOTAL' Sede,
          convert(VARCHAR(50) ,CAST(SUM(Valor) AS MONEY),1 ) Valor
          FROM CM_FacturadoJaime
          WHERE MES<>'$mes'";

        $result = $this->db->get_results($query);
        return $result;
    }

    public function GetSumMesActual_Refactura() {
        $query = "----------------------
        -- sum actual + refa
        ----------------------
            select 

          'TOTAL ANT+ACT' Sede,
          convert(VARCHAR(50) ,CAST(SUM(Valor) AS MONEY),1 ) Valor
          FROM CM_FacturadoJaime";

        $result = $this->db->get_results($query);
        return $result;
    }

    public function Finish() {
        $query = "delete from CM_FacturadoJaime";
        $this->db->query($query);
    }

    public function GetPendientePorFacturar($mes, $numero_mes, $ano) {
        $query = "-------------------------
--PENDIENTE POR FACTURAR
-------------------------
	SELECT '$mes' Mes,
				CASE HADM.IDSEDE WHEN '01' THEN 'CLD' 
                        WHEN '02' THEN 'CEMIC'
                        WHEN '03' THEN 'CSI'  END 'Sede',
				 convert(VARCHAR(50) ,CAST(SUM(HPRED.VALOREXCEDENTE) AS MONEY),1 ) 'Valor'
				FROM HADM 
							INNER JOIN HPRE  ON HADM.NOADMISION   = HPRE.NOADMISION
							INNER JOIN HPRED ON HPRE.NOPRESTACION = HPRED.NOPRESTACION  
				WHERE HADM.CLASEING = 'A'
				AND COALESCE(HPRED.NOCOBRABLE, 0) = 0
				AND HADM.CLASEING = 'A'
				AND HADM.FACTURABLE = 1
				AND YEAR(HADM.FECHAALTAMED) =" . $ano  . "
				AND MONTH(HADM.FECHAALTAMED) = " . $numero_mes  . "   --OJO 
				AND NOT EXISTS (SELECT *
                  FROM HPRE 
							INNER JOIN HPRED ON HPRE.NOPRESTACION = HPRED.NOPRESTACION
                            INNER JOIN FTR   ON HPRED.N_FACTURA   = FTR.N_FACTURA AND FTR.ESTADO = 'P'
                  WHERE FACTURADA = 1 
                  AND HPRE.NOADMISION = HADM.NOADMISION) AND NOT EXISTS (SELECT * 
                FROM HHAB 
				WHERE HHAB.NOADMISION = HADM.NOADMISION)
				GROUP BY HADM.IDSEDE";

        $result = $this->db->get_results($query);
        return $result;
    }
    public function GetSumPendientePorFacturar($numero_mes, $ano) {
        $query = "-----------------
--pendiente por facturar sumatoria total
-----------------
SELECT  '......' Mes,
		 'TOTAL' Sede,
				 convert(VARCHAR(50) ,CAST(SUM(HPRED.VALOREXCEDENTE) AS MONEY),1 ) 'Valor'
				FROM HADM 
							INNER JOIN HPRE  ON HADM.NOADMISION   = HPRE.NOADMISION
							INNER JOIN HPRED ON HPRE.NOPRESTACION = HPRED.NOPRESTACION  
				WHERE HADM.CLASEING = 'A'
				AND COALESCE(HPRED.NOCOBRABLE, 0) = 0
				AND HADM.CLASEING = 'A'
				AND HADM.FACTURABLE = 1
				AND YEAR(HADM.FECHAALTAMED) = " . $ano  . "
				AND MONTH(HADM.FECHAALTAMED) = " . $numero_mes  . "   --OJO 
				AND NOT EXISTS (SELECT *
                  FROM HPRE 
							INNER JOIN HPRED ON HPRE.NOPRESTACION = HPRED.NOPRESTACION
                            INNER JOIN FTR   ON HPRED.N_FACTURA   = FTR.N_FACTURA AND FTR.ESTADO = 'P'
                  WHERE FACTURADA = 1 
                  AND HPRE.NOADMISION = HADM.NOADMISION) AND NOT EXISTS (SELECT * 
                FROM HHAB 
				WHERE HHAB.NOADMISION = HADM.NOADMISION)";

        $result = $this->db->get_results($query);
        return $result;
    }
    

    public function GetAdmision($Admision) {
        $query = "SELECT HADM.NOADMISION
                    FROM HADM
                    WHERE 
                    HADM.NOADMISION = '" . $Admision . "'";

        return $this->db->get_results($query);
    }

}
