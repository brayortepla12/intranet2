<?php
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";
/**
 * Description of HDDAL
 *
 * @author DESARROLLO2
 */
class HDDAL {
    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL'], 'latin1');
    }

    public function __destruct() {
        $this->db->disconnect();
    }
    
    public function GetHDById($HDId) {
        return $this->db->objectBuilder()->rawQuery("SELECT hd.* FROM sa_hd AS hd 
        WHERE hd.HDId= $HDId");
    }
    
    public function GetDHDByHDId($HDId) {
        return $this->db->objectBuilder()->rawQuery("SELECT dhd.* FROM sa_dhd AS dhd 
        WHERE dhd.HDId= $HDId ORDER BY dhd.NOMBREAFI");
    }

    public function GetComidasByDHDId($HDId) {
        return $this->db->objectBuilder()->rawQuery("SELECT c.CDHDId, c.Distribucion, c.DistribucionId, c.Tipo, c.TipoId, c.Observacion FROM sa_distribucion as d
        left join sa_cdhd as c on c.DistribucionId = d.DistribucionId
        and c.DHDId = $HDId;");
    }
    
    public function GetHDs($Estado, $Dia, $Mes, $Year) {
        return $this->db->objectBuilder()->rawQuery("SELECT hd.* FROM sa_hd AS hd 
        WHERE YEAR(hd.Fecha) = $Year AND ((month(hd.Fecha) = '$Mes' and (day(hd.Fecha) = '$Dia' or '$Dia' = 'TODOS')) or '$Mes' = 'TODOS') 
        AND (((hd.Estado = '$Estado') OR ('$Estado' = 'TODOS')) 
        OR ('$Estado' = 'PENDIENTES' and (hd.Estado = 'Desayuno' or hd.Estado = 'Almuerzo' or hd.Estado = 'Cena')))
        ORDER BY hd.DESCRIPCION, hd.Fecha DESC");
    }

    public function GetHDsByUsuarioId(string $UsuarioId, string $Estado, string $Dia, string $Mes, string $Year) : ?array
    {
        return $this->db->objectBuilder()->rawQuery("SELECT hd.* FROM sa_hd AS hd 
        WHERE hd.UsuarioId = $UsuarioId AND YEAR(hd.Fecha) = $Year AND ((month(hd.Fecha) = '$Mes' and (day(hd.Fecha) = '$Dia' or '$Dia' = 'TODOS')) or '$Mes' = 'TODOS') 
        AND (((hd.Estado = '$Estado') OR ('$Estado' = 'TODOS')) 
        OR ('$Estado' = 'PENDIENTES' and (hd.Estado = 'Desayuno' or hd.Estado = 'Almuerzo' or hd.Estado = 'Cena')))
        ORDER BY hd.DESCRIPCION, hd.Fecha DESC");
    }

    public function GetHDsByUsuarioEmpresa(string $UsuarioId, string $CompletoSQL, string $Dia, string $Mes, string $Year) : ?array
    {
        return $this->db->objectBuilder()->rawQuery("SELECT hd.* FROM sa_hd as hd 
        STRAIGHT_JOIN sa_sector as s on s.SECTOR = hd.SECTOR
        STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId
        STRAIGHT_JOIN sa_empresausuario as eu on eu.EmpresaId = es.EmpresaId
        WHERE eu.UsuarioId = $UsuarioId 
        AND YEAR(hd.Fecha) = $Year AND ((month(hd.Fecha) = '$Mes' and (day(hd.Fecha) = '$Dia' or '$Dia' = 'TODOS')) or '$Mes' = 'TODOS')" . $CompletoSQL);
    }

    public function VerificarPaciente($NoAdmision, $Distribucion, $FechaAPreparar)
    {
        return $this->db->objectBuilder()->rawQuery("Select DHDId from sa_dhd as dhd WHERE dhd.NOADMISION = '$NoAdmision' and FIHD = DATE('$FechaAPreparar') AND $Distribucion is not null");
    }

    public function GetHDBySecDay($Sector, $Fecha) {
        return $this->db->objectBuilder()->rawQuery("SELECT hd.* FROM sa_hd AS hd 
        WHERE hd.Fecha = '$Fecha' AND hd.SECTOR = '$Sector'");
    }
    public function GetCantidadesAP($sql) {
        return $this->db->objectBuilder()->rawQuery("SELECT v.Descripcion, v.Abrv, sum(t.Cantidad) as Total FROM (
        $sql 
        ) as t
        STRAIGHT_JOIN sa_var as v on t.VarId = v.VariableId
        group by v.Descripcion");
    }
    
    public function GetVariables() {
        return $this->db->objectBuilder()->rawQuery("SELECT VariableId, Descripcion, Abrv from sa_var Order by Descripcion;");
    }

    public function GetEmpresas() {
        return $this->db->objectBuilder()->rawQuery("SELECT EmpresaId, NombreEmpresa from sa_empresa Order by NombreEmpresa;");
    }

    public function GetSectores() {
        return $this->db->objectBuilder()->rawQuery("SELECT SectorId, SECTOR as Sector, DESCRIPCION as Descripcion FROM sa_sector order by SECTOR;");
    }
    /**
     * QueryBuilder
     *
     * @param [type] $sql
     * @return array
     */
    public function GetExecuteQuery($sql) {
        return $this->db->objectBuilder()->rawQuery($sql);
    }

    public function VerificarMes($Mes, $Year)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT Dia from ct_mes where Mes = $Mes and annio = $Year limit 1");
    }

    public function GetDistribucion() {
        return $this->db->objectBuilder()->rawQuery("SELECT DistribucionId, Nombre, Orden, HasHoraLimite FROM polivalente.sa_distribucion;");
    }

    public function GetDistribucionByNombre($Nombre) {
        return $this->db->objectBuilder()->rawQuery("SELECT DistribucionId, Nombre, Orden, HasHoraLimite FROM polivalente.sa_distribucion where Nombre = '$Nombre';");
    }

    public function GetDistribucionById($DistribucionId) {
        return $this->db->objectBuilder()->rawQuery("SELECT DistribucionId, Nombre, Orden, HasHoraLimite FROM polivalente.sa_distribucion where DistribucionId = $DistribucionId;");
    }
    
    public function CreateHD($list) {
        $ids = $this->db->insertMulti("sa_hd", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function CreateMes($list) {
        $ids = $this->db->insertMulti("ct_mes", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function CreateDHD($list) {
        $ids = $this->db->insertMulti("sa_dhd", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateEDHD($list) {
        $ids = $this->db->insertMulti("sa_edhd", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateCDHD($list) {
        $ids = $this->db->insertMulti("sa_cdhd", $list);
        if (!$ids) {
            return 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function UpdateBulk($TableName, $columns, $data) {
        return $this->db->bulkUpdate($TableName, $columns, $data);
    }
    
    public function UpdateHD($list, $id) {
        $this->db->where('HDId', $id);
        if ($this->db->update('sa_hd', $list[0])) {
            return [true];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function UpdateDHD($list, $id) {
        $this->db->where('DHDId', $id);
        if ($this->db->update('sa_dhd', $list[0])) {
            return [true];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function GetEstadisticasDetalladas($Dia, $Mes, $Year) {
        $sql = "SELECT t.*, t.Desayuno + t.MM + t.Almuerzo + t.MT + t.Cena + t.MN  as TOTAL FROM (
            SELECT v.Abrv, v.Descripcion,
                (
                    SELECT count(dhd.DesayunoId) FROM sa_dhd as dhd where dhd.DesayunoId = v.VariableId AND dhd.CancelarDesayuno = 0 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) Desayuno,
                (
                    SELECT count(dhd.MMId) FROM sa_dhd as dhd where dhd.MMId = v.VariableId AND dhd.CancelarMM = 0 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) MM,
                (
                    SELECT count(dhd.AlmuerzoId) FROM sa_dhd as dhd where dhd.AlmuerzoId = v.VariableId AND dhd.CancelarAlmuerzo = 0  
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) Almuerzo,
                (
                    SELECT count(dhd.MTId) FROM sa_dhd as dhd where dhd.MTId = v.VariableId AND dhd.CancelarMT = 0 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) MT,
                (
                    SELECT count(dhd.CenaId) FROM sa_dhd as dhd where dhd.CenaId = v.VariableId AND dhd.CancelarCena = 0 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) Cena,
                (
                    SELECT count(dhd.MNId) FROM sa_dhd as dhd where dhd.MNId = v.VariableId AND dhd.CancelarMN = 0  
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) MN
             from sa_var as v
             order by v.Descripcion) as t
             
             UNION ALL
             SELECT '' Abrv, 'TOTAL' as DESCRIPCION, sum(t.Desayuno) Desayuno, sum(t.MM) MM
             , sum(t.Almuerzo) Almuerzo, sum(t.MT) MT, sum(t.Cena) Cena, sum(t.MN) MN
             , sum(t.Desayuno + t.MM + t.Almuerzo + t.MT + t.Cena + t.MN)  as TOTAL FROM (
            SELECT v.Abrv, v.Descripcion,
                (
                    SELECT count(dhd.DesayunoId) FROM sa_dhd as dhd where dhd.DesayunoId = v.VariableId AND dhd.CancelarDesayuno = 0 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) Desayuno,
                (
                    SELECT count(dhd.MMId) FROM sa_dhd as dhd where dhd.MMId = v.VariableId AND dhd.CancelarMM = 0   
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) MM,
                (
                    SELECT count(dhd.AlmuerzoId) FROM sa_dhd as dhd where dhd.AlmuerzoId = v.VariableId AND dhd.CancelarAlmuerzo = 0  
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) Almuerzo,
                (
                    SELECT count(dhd.MTId) FROM sa_dhd as dhd where dhd.MTId = v.VariableId AND dhd.CancelarMT = 0  
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) MT,
                (
                    SELECT count(dhd.CenaId) FROM sa_dhd as dhd where dhd.CenaId = v.VariableId AND dhd.CancelarCena = 0 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) Cena,
                (
                    SELECT count(dhd.MNId) FROM sa_dhd as dhd where dhd.MNId = v.VariableId AND dhd.CancelarMN = 0 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) MN
             from sa_var as v
             order by v.Descripcion) as t";
        return $this->db->objectBuilder()->rawQuery($sql);
    }

    public function GetEstadisticasDetalladasByEmpresa($Empresa, $Dia, $Mes, $Year) {
        $sql = "SELECT t.*, t.Desayuno + t.MM + t.Almuerzo + t.MT + t.Cena + t.MN  as TOTAL FROM (
            SELECT v.Abrv, v.Descripcion,
                (
                    SELECT count(dhd.DesayunoId) FROM sa_dhd as dhd
                    STRAIGHT_JOIN sa_hd as h on h.HDId = dhd.HDId
                    STRAIGHT_JOIN sa_sector as s on s.SECTOR = h.SECTOR
                    STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = $Empresa
                    where dhd.DesayunoId = v.VariableId AND dhd.EDesayuno = 'Preparado' 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) Desayuno,
                (
                    SELECT count(dhd.MMId) FROM sa_dhd as dhd 
                    STRAIGHT_JOIN sa_hd as h on h.HDId = dhd.HDId
                    STRAIGHT_JOIN sa_sector as s on s.SECTOR = h.SECTOR
                    STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = $Empresa
                    where dhd.MMId = v.VariableId AND dhd.EMM = 'Preparado' 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) MM,
                (
                    SELECT count(dhd.AlmuerzoId) FROM sa_dhd as dhd 
                    STRAIGHT_JOIN sa_hd as h on h.HDId = dhd.HDId
                    STRAIGHT_JOIN sa_sector as s on s.SECTOR = h.SECTOR
                    STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = $Empresa
                    where dhd.AlmuerzoId = v.VariableId AND dhd.EAlmuerzo = 'Preparado'
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) Almuerzo,
                (
                    SELECT count(dhd.MTId) FROM sa_dhd as dhd 
                    STRAIGHT_JOIN sa_hd as h on h.HDId = dhd.HDId
                    STRAIGHT_JOIN sa_sector as s on s.SECTOR = h.SECTOR
                    STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = $Empresa
                    where dhd.MTId = v.VariableId AND dhd.EMT = 'Preparado'
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) MT,
                (
                    SELECT count(dhd.CenaId) FROM sa_dhd as dhd 
                    STRAIGHT_JOIN sa_hd as h on h.HDId = dhd.HDId
                    STRAIGHT_JOIN sa_sector as s on s.SECTOR = h.SECTOR
                    STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = $Empresa
                    where dhd.CenaId = v.VariableId AND dhd.ECena = 'Preparado'
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) Cena,
                (
                    SELECT count(dhd.MNId) FROM sa_dhd as dhd 
                    STRAIGHT_JOIN sa_hd as h on h.HDId = dhd.HDId
                    STRAIGHT_JOIN sa_sector as s on s.SECTOR = h.SECTOR
                    STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = $Empresa
                    where dhd.MNId = v.VariableId AND dhd.EMN = 'Preparado'
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                ) MN
             from sa_var as v
             order by v.Descripcion
            ) as t
             
             UNION ALL
             SELECT '' Abrv, 'TOTAL' as DESCRIPCION, sum(t.Desayuno) Desayuno, sum(t.MM) MM
             , sum(t.Almuerzo) Almuerzo, sum(t.MT) MT, sum(t.Cena) Cena, sum(t.MN) MN
             , sum(t.Desayuno + t.MM + t.Almuerzo + t.MT + t.Cena + t.MN)  as TOTAL FROM (
             SELECT v.Abrv, v.Descripcion,
                (
                    SELECT count(dhd.DesayunoId) FROM sa_dhd as dhd
                    STRAIGHT_JOIN sa_hd as h on h.HDId = dhd.HDId
                    STRAIGHT_JOIN sa_sector as s on s.SECTOR = h.SECTOR
                    STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = $Empresa
                    where dhd.DesayunoId = v.VariableId AND dhd.EDesayuno = 'Preparado'
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                    and dhd.CancelarDesayuno = 0
                ) Desayuno,
                (
                    SELECT count(dhd.MMId) FROM sa_dhd as dhd 
                    STRAIGHT_JOIN sa_hd as h on h.HDId = dhd.HDId
                    STRAIGHT_JOIN sa_sector as s on s.SECTOR = h.SECTOR
                    STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = $Empresa
                    where dhd.MMId = v.VariableId AND dhd.EMM = 'Preparado' 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                    and dhd.CancelarMM = 0
                ) MM,
                (
                    SELECT count(dhd.AlmuerzoId) FROM sa_dhd as dhd 
                    STRAIGHT_JOIN sa_hd as h on h.HDId = dhd.HDId
                    STRAIGHT_JOIN sa_sector as s on s.SECTOR = h.SECTOR
                    STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = $Empresa
                    where dhd.AlmuerzoId = v.VariableId AND dhd.EAlmuerzo = 'Preparado' 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                    and dhd.CancelarAlmuerzo = 0
                ) Almuerzo,
                (
                    SELECT count(dhd.MTId) FROM sa_dhd as dhd 
                    STRAIGHT_JOIN sa_hd as h on h.HDId = dhd.HDId
                    STRAIGHT_JOIN sa_sector as s on s.SECTOR = h.SECTOR
                    STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = $Empresa
                    where dhd.MTId = v.VariableId AND dhd.EMT = 'Preparado' 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                    and dhd.CancelarMT = 0
                ) MT,
                (
                    SELECT count(dhd.CenaId) FROM sa_dhd as dhd 
                    STRAIGHT_JOIN sa_hd as h on h.HDId = dhd.HDId
                    STRAIGHT_JOIN sa_sector as s on s.SECTOR = h.SECTOR
                    STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = $Empresa
                    where dhd.CenaId = v.VariableId AND dhd.ECena = 'Preparado' 
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                    and dhd.CancelarCena = 0
                ) Cena,
                (
                    SELECT count(dhd.MNId) FROM sa_dhd as dhd 
                    STRAIGHT_JOIN sa_hd as h on h.HDId = dhd.HDId
                    STRAIGHT_JOIN sa_sector as s on s.SECTOR = h.SECTOR
                    STRAIGHT_JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = $Empresa
                    where dhd.MNId = v.VariableId AND dhd.EMN = 'Preparado'  
                    and ((dayofmonth(dhd.FIHD) = '$Dia' and '$Dia' <> 'TODOS') || '$Dia' = 'TODOS') and month(dhd.FIHD) = $Mes and year(dhd.FIHD) = $Year
                    and dhd.CancelarMN = 0
                ) MN
             from sa_var as v
             order by v.Descripcion
             ) as t";
        return $this->db->objectBuilder()->rawQuery($sql);
    }
}
