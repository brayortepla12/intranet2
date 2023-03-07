<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";require __DIR__ . "/../../vendor/autoload.php";

class RadicacionDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();
        $dotenv->required(['USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'HOST_SQLSERVER', 'PORT_SQLSERVER']);
        $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
    }

    public function GetRadicacion($Mes, $Anno) {
        $query = "-------------------------------
        --EJECUTO DETALLADO POR EPS
        -------------------------------
        SELECT 
        --FCXC.IDTERCERO 'IDTERCERO', 
        TER.RAZONSOCIAL  'RAZONSOCIAL', 
        COUNT(FCXCD.N_FACTURA) 'CANTIDAD', 
        REPLACE(convert(VARCHAR(50) ,CAST(SUM(FCXCD.VALORFACTURA) AS MONEY),1 ),'.00','') VALORFACTURA
        --convert(numeric,SUM(FCXCD.VALORFACTURANETO)) VALORFACTURANETO 
        FROM FCXC INNER JOIN FCXCD ON FCXC.CNSCXC = FCXCD.CNSCXC
                  INNER JOIN TER   ON FCXC.IDTERCERO = TER.IDTERCERO
        WHERE FCXC.INDRECIBIDO = 1
        AND YEAR(FCXC.F_RECIBIDO) = $Anno
        AND MONTH(FCXC.F_RECIBIDO) = $Mes
        AND FCXC.PROCEDENCIA = 'CARTERA'
        GROUP BY FCXC.IDTERCERO, TER.RAZONSOCIAL
        order by SUM(FCXCD.VALORFACTURA) desc";

        $result = $this->db->get_results($query);
        return $result;
    }
    public function GetTotalRadicacion($Mes, $Anno) {
        $query = "-------------------------
        --TOTALIZO EL DETALLADO
        -------------------------

        SELECT 
        '......' RAZON ,'TOTAL' CANTIDAD,
        --convert(numeric,SUM (FCXCD.VALORFACTURA)) VALORFACTURA
        REPLACE(convert(VARCHAR(50) ,CAST(SUM(FCXCD.VALORFACTURA) AS MONEY),1 ),'.00','') VALORFACTURA
        --convert(numeric,SUM(FCXCD.VALORFACTURANETO)) VALORFACTURANETO 
        FROM FCXC INNER JOIN FCXCD ON FCXC.CNSCXC = FCXCD.CNSCXC
                  INNER JOIN TER   ON FCXC.IDTERCERO = TER.IDTERCERO
        WHERE FCXC.INDRECIBIDO = 1
        AND YEAR(FCXC.F_RECIBIDO) = $Anno
        AND MONTH(FCXC.F_RECIBIDO) = $Mes
        AND FCXC.PROCEDENCIA = 'CARTERA'";

        $result = $this->db->get_results($query);
        return $result;
    }

}
