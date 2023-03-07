<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";require __DIR__ . "/../../vendor/autoload.php";

class FacturasEmitidasDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();
        $dotenv->required(['USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'HOST_SQLSERVER', 'PORT_SQLSERVER']);
        $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
    }

    public function GetFacturasEmitidasMES() {
        $query = "----------------
        --FACTURAS EMITIDAS
        -----------------

        SELECT USUSU.NOMBRE, COUNT(*) Cantidad
        FROM FTR INNER JOIN USUSU ON FTR.USUARIOFACTURA = USUSU.USUARIO
        WHERE FTR.F_FACTURA >= DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)
        AND FTR.F_FACTURA <= GETDATE()
        AND FTR.ESTADO = 'P'
        GROUP BY  USUSU.NOMBRE
        ORDER BY CANTIDAD DESC";

        $result = $this->db->get_results($query);
        return $result;
    }
    
    
    public function GetFacturasEmitidasMESTotal() {
        $query = "--------------------------
                --TOTAL FACTURAS EMITIDAS
                -----------------------

                SELECT 'Total ' total1, COUNT(*) Cantidad
                FROM FTR INNER JOIN USUSU ON FTR.USUARIOFACTURA = USUSU.USUARIO
                WHERE FTR.F_FACTURA >= DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0)
                AND FTR.F_FACTURA <= GETDATE()
                AND FTR.ESTADO = 'P'";

        $result = $this->db->get_results($query);
        return $result;
    }
    
    public function GetFacturasEmitidasHOY() {
        $query = "----------------
        --FACTURAS EMITIDAS
        -----------------

        SELECT FTR.USUARIOFACTURA, USUSU.NOMBRE, COUNT(*) Cantidad
        FROM FTR INNER JOIN USUSU ON FTR.USUARIOFACTURA = USUSU.USUARIO
        where CONVERT(VARCHAR,FTR.F_FACTURA,104)=CONVERT(VARCHAR,GETDATE(),104)
        AND FTR.ESTADO = 'P'
        GROUP BY FTR.USUARIOFACTURA, USUSU.NOMBRE
        ORDER BY CANTIDAD DESC";

        $result = $this->db->get_results($query);
        return $result;
    }
    
    
    public function GetFacturasEmitidasHOYTotal() {
        $query = "--------------------------
        --TOTAL FACTURAS EMITIDAS
        -----------------------

        SELECT 'Total ' Total, COUNT(*) Cantidad
        FROM FTR INNER JOIN USUSU ON FTR.USUARIOFACTURA = USUSU.USUARIO
        where CONVERT(VARCHAR,FTR.F_FACTURA,104)=CONVERT(VARCHAR,GETDATE(),104)
        AND FTR.F_FACTURA <= GETDATE()
        AND FTR.ESTADO = 'P'";

        $result = $this->db->get_results($query);
        return $result;
    }

}
