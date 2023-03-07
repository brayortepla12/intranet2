<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/db.php";require __DIR__ . "/../../vendor/autoload.php";

class CensosDAL {

    private $db;
    private $DATABASE;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['HOST_SQLSERVER', 'USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'PORT_SQLSERVER']);
        $this->DATABASE = $_ENV['DATABASE_SQLSERVER'];
        $this->db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
    }

    public function CensoPorPeriodo($From, $To) {
        $query = "SELECT --HADM.NOADMISION,TER.RAZONSOCIAL ,
		datename(month,HADM.FECHA) MES, datePart(month,HADM.FECHA) NumeroMes, COUNT(NOADMISION) CANTIDAD
        --INTO #HISTORICOADMIN
        FROM HADM 
        INNER JOIN TER   ON HADM.IDTERCERO=TER.IDTERCERO 
        WHERE len(HADM.NOADMISION)=10 and
        HADM.CLASEING ='A' AND HADM.FACTURABLE=1 
        AND HADM.FECHA BETWEEN '$From' AND '$To' AND HADM.IDTERCERO LIKE '%' 
        GROUP BY --HADM.NOADMISION,TER.RAZONSOCIAL,
		datename(month,HADM.FECHA), datePart(month,HADM.FECHA)
		UNION
		SELECT 'TOTAL' MES, '100',SUM(CANTIDAD) FROM (SELECT --HADM.NOADMISION,TER.RAZONSOCIAL ,
		datename(month,HADM.FECHA) mes, datePart(month,HADM.FECHA) NumeroMes, COUNT(NOADMISION) CANTIDAD
        --INTO #HISTORICOADMIN
        FROM HADM 
        INNER JOIN TER   ON HADM.IDTERCERO=TER.IDTERCERO 
        WHERE len(HADM.NOADMISION)=10 and
        HADM.CLASEING ='A' AND HADM.FACTURABLE=1 
        AND HADM.FECHA BETWEEN '$From' AND '$To' AND HADM.IDTERCERO LIKE '%' 
        GROUP BY --HADM.NOADMISION,TER.RAZONSOCIAL,
		datename(month,HADM.FECHA), datePart(month,HADM.FECHA)) CANTIDAD
		order by 2;";
        $result = $this->db->get_results($query);
        return $result;
    }

    public function CensoPorEPS() {
        $query = "";
        if ($this->DATABASE !== 'KPRADO') {
            $query = "SELECT TER.RAZONSOCIAL AS EPS, count(*) 'Total'
            FROM HHAB INNER JOIN HADM ON HADM.NOADMISION = HHAB.NOADMISION
                        INNER JOIN TER  ON HADM.IDTERCERO  = TER.IDTERCERO
            WHERE HHAB.CLASE='CAMA' AND HADM.CERRADA=0
            GROUP BY TER.RAZONSOCIAL
            order by 2 desc";
        } else {
            $query = "SELECT TER.RAZONSOCIAL COLLATE Latin1_General_CI_AS AS EPS, count(*) 'Total'
            FROM HHAB INNER JOIN HADM ON HADM.NOADMISION = HHAB.NOADMISION
                        INNER JOIN TER  ON HADM.IDTERCERO  = TER.IDTERCERO
            WHERE HHAB.CLASE='CAMA' AND HADM.CERRADA=0
            GROUP BY TER.RAZONSOCIAL
			UNION ALL
			SELECT KUNIONT.dbo.TER.RAZONSOCIAL + '( UT )' COLLATE Latin1_General_CI_AS AS EPS, count(*) 'Total'
            FROM KUNIONT.dbo.HHAB INNER JOIN KUNIONT.dbo.HADM ON KUNIONT.dbo.HADM.NOADMISION = KUNIONT.dbo.HHAB.NOADMISION
                        INNER JOIN KUNIONT.dbo.TER  ON KUNIONT.dbo.HADM.IDTERCERO  = KUNIONT.dbo.TER.IDTERCERO
            WHERE KUNIONT.dbo.HHAB.CLASE='CAMA' AND KUNIONT.dbo.HADM.CERRADA=0 
            GROUP BY KUNIONT.dbo.TER.RAZONSOCIAL
            order by 2 desc ;";
        }
        $result = $this->db->get_results($query);
        return $result;
    }

    public function TotalCensoPorEPS() {
        $query = "";
        if ($this->DATABASE !== 'KPRADO') {
            $query = "SELECT 'TOTAL' EPS,
                         CONCAT(count(*), ' / 400 CAMAS') 'Total',
                        count(*) * 100 / 400 AS 'PorcentajeOcupacion'
                FROM HHAB INNER JOIN HADM ON HADM.NOADMISION = HHAB.NOADMISION
                            INNER JOIN TER  ON HADM.IDTERCERO = TER.IDTERCERO
                WHERE HHAB.CLASE='CAMA' AND HADM.CERRADA=0";
        } else {
            $query = "SELECT 'TOTAL' AS 'EPS', SUM(T1.Total) as 'Total', SUM(T1.Total) * 100 / 467 AS 'PorcentajeOcupacion'  FROM (
                SELECT 'TOTAL' EPS,
                    count(*) 'Total'
                FROM HHAB INNER JOIN HADM ON HADM.NOADMISION = HHAB.NOADMISION
                        INNER JOIN TER  ON HADM.IDTERCERO = TER.IDTERCERO
                WHERE HHAB.CLASE='CAMA' AND HADM.CERRADA=0
                UNION ALL
                SELECT 'TOTAL' EPS,
                count(*) 'Total'
                FROM KUNIONT.dbo.HHAB INNER JOIN KUNIONT.dbo.HADM ON KUNIONT.dbo.HADM.NOADMISION = KUNIONT.dbo.HHAB.NOADMISION
                        INNER JOIN KUNIONT.dbo.TER  ON KUNIONT.dbo.HADM.IDTERCERO = KUNIONT.dbo.TER.IDTERCERO
                WHERE KUNIONT.dbo.HHAB.CLASE='CAMA' AND KUNIONT.dbo.HADM.CERRADA=0) AS T1";
        }
        $result = $this->db->get_results($query);
        return $result;
    }

    public function CensoPorSector() {
        $query = "";
        if ($this->DATABASE !== 'KPRADO') {
            $query = "SELECT o.Sector,o.Descripcion, o.Total,o.Capacidad,(o.Capacidad - o.Total) 'DIF' from 
            (SELECT HHAB.SECTOR 'Sector',t1.DESCRIPCION 'Descripcion',count(*) 'Total',t2.Capacidad 'Capacidad' --, sum(count(*)  - t2.Capacidad)Disponible
            FROM HHAB 
            inner join hadm ON hadm.NOADMISION=HHAB.NOADMISION
            inner join (select HHAB.SECTOR,HHAB.DESCRIPCION 
                        from HHAB WHERE HHAB.CLASE='sector') t1 on t1.SECTOR=HHAB.SECTOR
            left join _CM_CAPACIDAD t2 on t1.SECTOR=t2.Sector
            WHERE HHAB.CLASE='CAMA' and HADM.CERRADA=0
            group by HHAB.SECTOR,t1.DESCRIPCION,t2.Capacidad) o
            order by 3 desc";
        } else {
            $query = "SELECT o.Sector,o.Descripcion, o.Total,o.Capacidad,(o.Capacidad - o.Total) 'DIF' from 
            (
			SELECT HHAB.SECTOR COLLATE Latin1_General_CI_AS  'Sector',t1.DESCRIPCION  COLLATE Latin1_General_CI_AS 'Descripcion',count(*) 'Total',ISNULL(t2.Capacidad COLLATE Latin1_General_CI_AS, 0) 'Capacidad'
            FROM HHAB 
            inner join hadm ON hadm.NOADMISION=HHAB.NOADMISION
            inner join (select HHAB.SECTOR,HHAB.DESCRIPCION 
                        from HHAB WHERE HHAB.CLASE='sector') t1 on t1.SECTOR=HHAB.SECTOR
            left join _CM_CAPACIDAD t2 on t1.SECTOR=t2.Sector
            WHERE HHAB.CLASE='CAMA' and HADM.CERRADA=0
            group by HHAB.SECTOR,t1.DESCRIPCION,t2.Capacidad

			UNION ALL

			SELECT KUNIONT.dbo.HHAB.SECTOR + '(UT)'  COLLATE Latin1_General_CI_AS 'Sector',t1.DESCRIPCION + '(UT)' COLLATE Latin1_General_CI_AS 'Descripcion',count(*) 'Total',ISNULL(t2.Capacidad COLLATE Latin1_General_CI_AS, 0) 'Capacidad'
            FROM KUNIONT.dbo.HHAB 
            inner join KUNIONT.dbo.hadm ON KUNIONT.dbo.hadm.NOADMISION=KUNIONT.dbo.HHAB.NOADMISION
            inner join (select KUNIONT.dbo.HHAB.SECTOR,KUNIONT.dbo.HHAB.DESCRIPCION 
                        from KUNIONT.dbo.HHAB WHERE KUNIONT.dbo.HHAB.CLASE='sector') t1 on t1.SECTOR=KUNIONT.dbo.HHAB.SECTOR
            left join KUNIONT.dbo._CM_CAPACIDAD t2 on t1.SECTOR=t2.Sector
            WHERE KUNIONT.dbo.HHAB.CLASE='CAMA' and KUNIONT.dbo.HADM.CERRADA=0
            group by KUNIONT.dbo.HHAB.SECTOR,t1.DESCRIPCION,t2.Capacidad
			) o";
        }
        $query = "";

        $result = $this->db->get_results($query);
        return $result;
    }

    public function GetTotalCensoPorSector() {
        $query = "";
        if ($this->DATABASE !== 'KPRADO') {
            $query = "SELECT '......' Sector ,'TOTAL' Descripcion,
            count(*) 'Total'
             FROM HHAB 
            inner join hadm ON hadm.NOADMISION=HHAB.NOADMISION
            WHERE HHAB.CLASE='CAMA' and HADM.CERRADA=0";
        } else {
            $query = "SELECT T1.Sector, T1.Descripcion, SUM(T1.Total) AS 'Total' FROM (
                SELECT '......' Sector ,'TOTAL' Descripcion,
                count(*) 'Total'
                 FROM HHAB 
                inner join hadm ON hadm.NOADMISION=HHAB.NOADMISION
                WHERE HHAB.CLASE='CAMA' and HADM.CERRADA=0
        
                UNION ALL 
        
                SELECT '......' Sector ,'TOTAL' Descripcion,
                count(*) 'Total'
                 FROM KUNIONT.dbo.HHAB 
                inner join KUNIONT.dbo.hadm ON KUNIONT.dbo.hadm.NOADMISION=KUNIONT.dbo.HHAB.NOADMISION
                WHERE KUNIONT.dbo.HHAB.CLASE='CAMA' and KUNIONT.dbo.HADM.CERRADA=0) T1
                GROUP BY T1.Sector, T1.Descripcion";
        }

        $result = $this->db->get_results($query);
        return $result;
    }

}
