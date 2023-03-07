<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
include_once dirname(__FILE__) . "/db.php";
require __DIR__ . "/../../vendor/autoload.php";

class HistoriaDAL {

    private $db;
    private $dbSQLSERVER;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'HOST_SQLSERVER', 'PORT_SQLSERVER']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
        $this->dbSQLSERVER = "";
    }

    public function __destruct() {
        $this->db->disconnect();
    }

    public function GetHistoriaCKrystalosByNoAdmision($data) {
        $this->dbSQLSERVER = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
        $query = "
            SELECT HADM.NOADMISION, AFI.PNOMBRE, AFI.SNOMBRE, AFI.PAPELLIDO, 
            AFI.SAPELLIDO, AFI.TIPO_DOC, AFI.IDAFILIADO, 
            HADM.FECHAALTA as FECHAALTAADMINISTRATIVA, HADM.FECHAALTAMED, HADM.CERRADA,
            TER.RAZONSOCIAL AS EPS, H2.DESCRIPCION AS SECTOR
            FROM HADM 
            INNER JOIN AFI on AFI.IDAFILIADO = HADM.IDAFILIADO
            INNER JOIN TER on TER.IDTERCERO = HADM.IDTERCERO
            LEFT OUTER JOIN HHAB on HADM.HABCAMA = HHAB.HABCAMA
            LEFT OUTER JOIN HHAB H2 ON HHAB.SECTOR = H2.HABCAMA
            WHERE
            HADM.NOADMISION = '$data'";
        $result = $this->dbSQLSERVER->get_results($query);
        return $result;
    }

    public function GetAutoCompleteHC($data, $top) {
//        phpinfo();
        $this->dbSQLSERVER = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
        
        $query = "
            SELECT TOP $top HADM.NOADMISION 
            FROM HADM 
            WHERE
            HADM.NOADMISION LIKE '%$data%'";
        $result = $this->dbSQLSERVER->get_results($query);
        return $result;
    }
    
    public function GetLiteHistoriaByAdmision($NoAdmision){
        return $this->db->objectBuilder()->rawQuery("select HistoriaId, NOADMISION from thc_historia where NOADMISION LIKE '%$NoAdmision%'");
    }
    
    public function GetHistoriaByAdmision($NoAdmision){
        return $this->db->objectBuilder()->rawQuery("select * from thc_historia where NOADMISION = '$NoAdmision'");
    }
    
    public function GetHistoriaById($HistoriaId){
        return $this->db->objectBuilder()->rawQuery("select * from thc_historia where HistoriaId = '$HistoriaId'");
    }
    
    
    public function GetGrupoByUsuarioId($UsuarioId){
        #if(th.FechaFin IS NULL, datediff(now(), th.FechaRecibido ), datediff(th.FechaFin, th.FechaRecibido)) as DiferenciaD
        return $this->db->objectBuilder()->rawQuery("SELECT GrupoId FROM polivalente.thc_grupousuario where UsuarioId = $UsuarioId;");
    }
    
    public function GetTrazabilidadByHistoriaId($HistoriaId){
        #if(th.FechaFin IS NULL, datediff(now(), th.FechaRecibido ), datediff(th.FechaFin, th.FechaRecibido)) as DiferenciaD
        return $this->db->objectBuilder()->rawQuery("SELECT th.THistoriaId, g.Nombre as Grupo, th.UsuarioId, th.NombreUsuario, 
        th.UsuarioRecibeId, th.Fecha, 
        th.FechaFin, th.IsRecibido, th.Estado
        FROM polivalente.thc_thistoria as th
        INNER JOIN thc_grupo as g on th.GrupoId = g.GrupoId
        where th.HistoriaId = $HistoriaId;");
    }
    
    public function GetHistoriasPR($UsuarioId){
        return $this->db->objectBuilder()->rawQuery("SELECT h.HistoriaId, h.NOADMISION, h.PNOMBRE, h.PAPELLIDO, h.EPS, th.UsuarioId as UsuarioQueEntregoId, 
        CONCAT(th.NombreUsuario, ' - ', g.Nombre)  as Paquete, th.IsRecibido
        FROM thc_thistoria as th 
        inner join thc_historia as h on h.HistoriaId = th.HistoriaId
        inner join thc_grupo as g on g.GrupoId = th.GrupoId
        where th.UsuarioRecibeId = $UsuarioId and (th.IsRecibido IS NULL or th.IsRecibido = 0) and th.FechaFin IS NULL ORDER BY th.UsuarioId ASC;");
    }
    
    public function GetMisHistorias($UsuarioId){
        return $this->db->objectBuilder()->rawQuery("SELECT h.HistoriaId, h.NOADMISION, h.PNOMBRE, h.PAPELLIDO, h.EPS, th.UsuarioId as UsuarioQueEntregoId, 
        th.NombreUsuario as UsuarioQueEntrego
        FROM thc_thistoria as th 
        inner join thc_historia as h on h.HistoriaId = th.HistoriaId
        where th.UsuarioId = $UsuarioId and th.IsRecibido = 1 and th.FechaFin IS NULL ORDER BY th.UsuarioId ASC;");
    }
    
    public function CreateHistoria($list) {
        $ids = $this->db->insertMulti("thc_historia", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateTHistoria($list) {
        $ids = $this->db->insertMulti("thc_thistoria", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function FinTHistoria($list, $UsuarioId, $HistoriaId) {
        $this->db->where('UsuarioRecibeId', $UsuarioId);
        $this->db->where ("HistoriaId", $HistoriaId);
        $this->db->where ("FechaFin", NULL, 'IS');
        if ($this->db->update('thc_thistoria', $list[0])) {
            return $list;
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    public function UpdateTHistoria($list,$THistoriaId) {
        $this->db->where('THistoriaId', $THistoriaId);
        if ($this->db->update('thc_thistoria', $list[0])) {
            return $list;
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

}
