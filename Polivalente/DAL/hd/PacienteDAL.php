<?php
include_once dirname(__FILE__) . "/db.php";
require __DIR__ . "/../../vendor/autoload.php";

class PacienteDAL {

    private $db;
    private $DATABASE;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['HOST_SQLSERVER', 'USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'PORT_SQLSERVER']);
        $this->DATABASE = $_ENV['DATABASE_SQLSERVER'];
        $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
    }

    public function GetPacientesBySector($Sector) {
        $query = "";
        if ($this->DATABASE !== "KPRADO") {
            $query = "SELECT T.* FROM (SELECT TOP 1000 hhab.habcama,
                HADM.NOADMISION, 
                HADM.FECHA,
                HADM.IDAFILIADO, 
                afi.NOMBREAFI,
                CEN.DESCRIPCION AS TIPOESTANCIA, 
                HHAB.SECTOR, 
                A.DESCRIPCION, 
                HADM.IDTERCERO, 
                TER.RAZONSOCIAL, 
                AFI.SEXO,
                HADM.ESTADOPSALIDA,
                afi.FNACIMIENTO, 
                1 as CanDesayunar,
                1 as CanAlmorzar, 
                1 as CanCenar, 
                        afi.EDAD COLLATE Latin1_General_CI_AS AS 'EDAD',  
                ROW_NUMBER() OVER(PARTITION BY HADM.IDAFILIADO ORDER BY HADM.FECHA DESC) rn
                FROM HHAB INNER JOIN HADM   ON HHAB.NOADMISION =  HADM.NOADMISION
                        INNER JOIN AFI    ON HADM.IDAFILIADO = AFI.IDAFILIADO
                        INNER JOIN TER    ON HADM.IDTERCERO  = TER.IDTERCERO
                        INNER JOIN CEN    ON HHAB.CCOSTO     = CEN.CCOSTO 
                        INNER JOIN HHAB A ON HHAB.SECTOR     = A.HABCAMA AND A.CLASE = 'Sector'
                WHERE HHAB.CLASE = 'Cama'
                AND hhab.ESTADOHAB = 'Ocupada'
                AND HHAB.SECTOR = '$Sector'
                ORDER BY afi.NOMBREAFI) 
                as T WHERE T.rn = 1";
        } else {
            $query = "SELECT * FROM (
                SELECT  hhab.habcama COLLATE Latin1_General_CI_AS AS 'habcama', 
            HADM.NOADMISION COLLATE Latin1_General_CI_AS AS 'NOADMISION', 
            HADM.FECHA,
            HADM.IDAFILIADO COLLATE Latin1_General_CI_AS AS 'IDAFILIADO', 
            afi.NOMBREAFI COLLATE Latin1_General_CI_AS AS 'NOMBREAFI', 
            CEN.DESCRIPCION COLLATE Latin1_General_CI_AS AS TIPOESTANCIA, 
            HHAB.SECTOR COLLATE Latin1_General_CI_AS AS 'SECTOR', 
            A.DESCRIPCION COLLATE Latin1_General_CI_AS AS 'DESCRIPCION', 
            HADM.IDTERCERO COLLATE Latin1_General_CI_AS AS 'IDTERCERO', 
            TER.RAZONSOCIAL COLLATE Latin1_General_CI_AS AS 'RAZONSOCIAL', 
            AFI.SEXO COLLATE Latin1_General_CI_AS AS 'SEXO',
            HADM.ESTADOPSALIDA COLLATE Latin1_General_CI_AS AS 'ESTADOPSALIDA',
            afi.FNACIMIENTO, 
            ROW_NUMBER() OVER(PARTITION BY HADM.IDAFILIADO ORDER BY HADM.FECHA DESC) rn,
            1 as CanDesayunar,
            1 as CanAlmorzar, 
            1 as CanCenar, 
                        afi.EDAD COLLATE Latin1_General_CI_AS AS 'EDAD'    
            FROM KPRADO.dbo.HHAB INNER JOIN KPRADO.dbo.HADM   ON HHAB.NOADMISION =  HADM.NOADMISION
                        INNER JOIN KPRADO.dbo.AFI    ON HADM.IDAFILIADO = AFI.IDAFILIADO
                        INNER JOIN KPRADO.dbo.TER    ON HADM.IDTERCERO  = TER.IDTERCERO
                        INNER JOIN KPRADO.dbo.CEN    ON HHAB.CCOSTO     = CEN.CCOSTO 
                        INNER JOIN KPRADO.dbo.HHAB A ON HHAB.SECTOR     = A.HABCAMA AND A.CLASE = 'Sector'
            WHERE KPRADO.dbo.HHAB.CLASE = 'Cama'
            AND KPRADO.dbo.hhab.ESTADOHAB = 'Ocupada'
            AND KPRADO.dbo.HHAB.SECTOR = '$Sector'
            
            UNION ALL
            
            SELECT  hhab.habcama COLLATE Latin1_General_CI_AS AS 'habcama', 
            HADM.NOADMISION COLLATE Latin1_General_CI_AS AS 'NOADMISION', 
            HADM.FECHA,
            HADM.IDAFILIADO COLLATE Latin1_General_CI_AS AS 'IDAFILIADO', 
            afi.NOMBREAFI COLLATE Latin1_General_CI_AS AS 'NOMBREAFI', 
            CEN.DESCRIPCION COLLATE Latin1_General_CI_AS AS TIPOESTANCIA, 
            HHAB.SECTOR COLLATE Latin1_General_CI_AS AS 'SECTOR', 
            A.DESCRIPCION COLLATE Latin1_General_CI_AS AS 'DESCRIPCION', 
            HADM.IDTERCERO COLLATE Latin1_General_CI_AS AS 'IDTERCERO', 
            TER.RAZONSOCIAL COLLATE Latin1_General_CI_AS AS 'RAZONSOCIAL', 
            AFI.SEXO COLLATE Latin1_General_CI_AS AS 'SEXO',
            HADM.ESTADOPSALIDA COLLATE Latin1_General_CI_AS AS 'ESTADOPSALIDA',
            afi.FNACIMIENTO, 
            ROW_NUMBER() OVER(PARTITION BY HADM.IDAFILIADO ORDER BY HADM.FECHA DESC) rn,
            1 as CanDesayunar,
            1 as CanAlmorzar, 
            1 as CanCenar, 
                        afi.EDAD COLLATE Latin1_General_CI_AS AS 'EDAD'     
            FROM KUNIONT.dbo.HHAB INNER JOIN KUNIONT.dbo.HADM   ON HHAB.NOADMISION =  HADM.NOADMISION
                        INNER JOIN KUNIONT.dbo.AFI    ON HADM.IDAFILIADO = AFI.IDAFILIADO
                        INNER JOIN KUNIONT.dbo.TER    ON HADM.IDTERCERO  = TER.IDTERCERO
                        INNER JOIN KUNIONT.dbo.CEN    ON HHAB.CCOSTO     = CEN.CCOSTO 
                        INNER JOIN KUNIONT.dbo.HHAB A ON HHAB.SECTOR     = A.HABCAMA AND A.CLASE = 'Sector'
            WHERE KUNIONT.dbo.HHAB.CLASE = 'Cama'
            AND KUNIONT.dbo.hhab.ESTADOHAB = 'Ocupada'
            AND KUNIONT.dbo.HHAB.SECTOR = '$Sector') AS T WHERE T.rn = 1 ORDER BY NOMBREAFI";
        }
        $result = $this->db->get_results($query);
        return $result;
    }

    public function GetPacienteByNoAdmision($NoAdmision) {
        $query = "";
        if ($this->DATABASE !== "KPRADO") {
            $query = "SELECT  hhab.habcama,HADM.NOADMISION, HADM.FECHA,HADM.IDAFILIADO, afi.NOMBREAFI,CEN.DESCRIPCION AS TIPOESTANCIA, 
                        HHAB.SECTOR, A.DESCRIPCION, HADM.IDTERCERO, TER.RAZONSOCIAL, AFI.SEXO,HADM.ESTADOPSALIDA,afi.FNACIMIENTO, 
                        1 as CanDesayunar,
                        1 as CanAlmorzar, 
                        1 as CanCenar, 
                        afi.EDAD  COLLATE Latin1_General_CI_AS AS 'EDAD'
                    FROM HHAB INNER JOIN HADM   ON HHAB.NOADMISION =  HADM.NOADMISION
                        INNER JOIN AFI    ON HADM.IDAFILIADO = AFI.IDAFILIADO
                        INNER JOIN TER    ON HADM.IDTERCERO  = TER.IDTERCERO
                        INNER JOIN CEN    ON HHAB.CCOSTO     = CEN.CCOSTO 
                        INNER JOIN HHAB A ON HHAB.SECTOR     = A.HABCAMA AND A.CLASE = 'Sector'
                    WHERE HHAB.CLASE = 'Cama'
                        AND hhab.ESTADOHAB = 'Ocupada'
                        AND HADM.NOADMISION LIKE '%$NoAdmision'
                    ORDER BY afi.NOMBREAFI";#--HHAB.IDSEDE, HHAB.CCOSTO, HHAB.SECTOR
        } else {
            $query = "SELECT * FROM (
                SELECT  hhab.habcama COLLATE Latin1_General_CI_AS AS 'habcama', 
                        HADM.NOADMISION COLLATE Latin1_General_CI_AS AS 'NOADMISION', 
                        HADM.FECHA,
                        HADM.IDAFILIADO COLLATE Latin1_General_CI_AS AS 'IDAFILIADO', 
                        afi.NOMBREAFI COLLATE Latin1_General_CI_AS AS 'NOMBREAFI', 
                        CEN.DESCRIPCION COLLATE Latin1_General_CI_AS AS TIPOESTANCIA, 
                        HHAB.SECTOR COLLATE Latin1_General_CI_AS AS 'SECTOR', 
                        A.DESCRIPCION COLLATE Latin1_General_CI_AS AS 'DESCRIPCION', 
                        HADM.IDTERCERO COLLATE Latin1_General_CI_AS AS 'IDTERCERO', 
                        TER.RAZONSOCIAL COLLATE Latin1_General_CI_AS AS 'RAZONSOCIAL', 
                        AFI.SEXO COLLATE Latin1_General_CI_AS AS 'SEXO',
                        HADM.ESTADOPSALIDA COLLATE Latin1_General_CI_AS AS 'ESTADOPSALIDA',
                        afi.FNACIMIENTO, 
                        1 as CanDesayunar,
                        1 as CanAlmorzar, 
                        1 as CanCenar, 
                        afi.EDAD COLLATE Latin1_General_CI_AS AS 'EDAD'  
                    FROM HHAB 
                        INNER JOIN HADM   ON HHAB.NOADMISION =  HADM.NOADMISION
                        INNER JOIN AFI    ON HADM.IDAFILIADO = AFI.IDAFILIADO
                        INNER JOIN TER    ON HADM.IDTERCERO  = TER.IDTERCERO
                        INNER JOIN CEN    ON HHAB.CCOSTO     = CEN.CCOSTO 
                        INNER JOIN HHAB A ON HHAB.SECTOR     = A.HABCAMA AND A.CLASE = 'Sector'
                    WHERE HHAB.CLASE = 'Cama'
                        AND hhab.ESTADOHAB = 'Ocupada'
                        AND HADM.NOADMISION LIKE '%$NoAdmision'
                
                    UNION ALL
                
                    SELECT  hhab.habcama COLLATE Latin1_General_CI_AS AS 'habcama', 
                        HADM.NOADMISION COLLATE Latin1_General_CI_AS AS 'NOADMISION', 
                        HADM.FECHA,
                        HADM.IDAFILIADO COLLATE Latin1_General_CI_AS AS 'IDAFILIADO', 
                        afi.NOMBREAFI COLLATE Latin1_General_CI_AS AS 'NOMBREAFI', 
                        CEN.DESCRIPCION COLLATE Latin1_General_CI_AS AS TIPOESTANCIA, 
                        HHAB.SECTOR COLLATE Latin1_General_CI_AS AS 'SECTOR', 
                        A.DESCRIPCION COLLATE Latin1_General_CI_AS AS 'DESCRIPCION', 
                        HADM.IDTERCERO COLLATE Latin1_General_CI_AS AS 'IDTERCERO', 
                        TER.RAZONSOCIAL COLLATE Latin1_General_CI_AS AS 'RAZONSOCIAL', 
                        AFI.SEXO COLLATE Latin1_General_CI_AS AS 'SEXO',
                        HADM.ESTADOPSALIDA COLLATE Latin1_General_CI_AS AS 'ESTADOPSALIDA',
                        afi.FNACIMIENTO, 
                    1 as CanDesayunar,
                    1 as CanAlmorzar, 
                    1 as CanCenar, 
                        afi.EDAD  COLLATE Latin1_General_CI_AS AS 'EDAD'  
                    FROM KUNIONT.dbo.HHAB 
                        INNER JOIN KUNIONT.dbo.HADM   ON HHAB.NOADMISION =  HADM.NOADMISION
                        INNER JOIN KUNIONT.dbo.AFI    ON HADM.IDAFILIADO = AFI.IDAFILIADO
                        INNER JOIN KUNIONT.dbo.TER    ON HADM.IDTERCERO  = TER.IDTERCERO
                        INNER JOIN KUNIONT.dbo.CEN    ON HHAB.CCOSTO     = CEN.CCOSTO 
                        INNER JOIN KUNIONT.dbo.HHAB A ON HHAB.SECTOR     = A.HABCAMA AND A.CLASE = 'Sector'
                    WHERE KUNIONT.dbo.HHAB.CLASE = 'Cama'
                        AND KUNIONT.dbo.hhab.ESTADOHAB = 'Ocupada'
                        AND KUNIONT.dbo.HADM.NOADMISION LIKE '%$NoAdmision') AS T ORDER BY NOMBREAFI";
        }
        $result = $this->db->get_results($query);
        return $result;
    }

    
}

