<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class RondaVerificacionDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateRondaVerificacion($list) {
        $ids = $this->db->insertMulti("cm_rondaverificacion", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateConsecutivoRondaVerificacion($list) {
        $ids = $this->db->insertMulti("cm_Consecutivo", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function UpdateRondaVerificacion($list, $id) {
        $this->db->where('RondaVerificacionId', $id);
        if ($this->db->update('cm_rondaverificacion', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function GetRondaVerificaciones() {
        return $this->db->jsonBuilder()->rawQuery("select *, if(now() <= TIMESTAMP(Fecha,'18:00:00'), true, false) as Editable from cm_rondaverificacion order by RondaVerificacionId desc;");
    }
    
    public function GetEstadisticasUnidosis($Mes, $Year, $Estado) {
        return $this->db->objectBuilder()->rawQuery("select m.MedicamentoId, r.Fecha, m.Nombre, m.Concentracion, m.NombreAbreviado, sum(dr.Cantidad) as CantidadAPreparar, 
        ROUND(Sum(IF(m.Concentracion <> 0,(dr.Dosis * m.VolumenFinal) / m.Concentracion /*volumen a tomar*/, 0) * (dr.Cantidad)) /  m.VolumenFinal , 2) as CantidadUtilizada
        , m.CodigoKrystalos, m.PrecioCompra
         from cm_detallerondaverificacion as dr
        STRAIGHT_JOIN cm_rondaverificacion as r on dr.RondaVerificacionId = r.RondaVerificacionId
        STRAIGHT_JOIN cm_medicamento as m on m.MedicamentoId = dr.MedicamentoId
        where MONTH(r.Fecha) = $Mes and YEAR(r.Fecha) = $Year and r.TipoRonda= '$Estado' and dr.Estado = 'Activo' and dr.EstadoPaciente <> 'Suspender'
        group by m.Nombre, r.Fecha order by r.Fecha DESC, m.Nombre");
    }
    
    public function getPrecioByCodigoMedicamento($Codigo, $db) {
//        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
        // IART.IDARTICULO , IART.DESCRIPCION , IART.IDSERVICIO, SER.DESCSERVICIO  , IMOV.NODOCUMENTO , IMOV.FECHAMOV , 
        $query = "select IMOVH.PCOSTO, IMOV.FECHAMOV
        from IART inner join IMOVH on IMOVH.IDARTICULO=IART.IDARTICULO 
         INNER JOIN SER ON SER.IDSERVICIO =IART.IDSERVICIO 
         inner join IMOV on IMOVH.CNSMOV=IMOV.CNSMOV 
         inner join (select MAX(imov.fechamov) FECHA  , IMOVH.IDARTICULO  from IMOVH inner join IMOV on IMOVH.CNSMOV=IMOV.CNSMOV
            where IMOV.ESTADO=1
             and IMOVH.ESTADO=1
             and IMOV.IDTIPOMOV ='07' and IMOVH.IDARTICULO = '$Codigo'
            group by IMOVH.IDARTICULO   
            ) FECHADO
          ON IMOV.FECHAMOV = FECHADO.FECHA  AND IMOVH.IDARTICULO =FECHADO.IDARTICULO 
        where IMOV.ESTADO=1
             and IMOVH.ESTADO=1
             and IMOV.IDTIPOMOV ='07' and IART.IDARTICULO = '$Codigo';";
        $result = $db->get_results($query);
        return $result;
    }

    public function GetConsecutivoForRondaByMedicamento($list) {
        return $this->db->objectBuilder()->rawQuery("select max(Consecutivo) as Consecutivo from cm_consecutivo as c 
	where 
	(c.TipoMedicamentoId = $list->TipoMedicamentoId 
	and c.Mes = $list->Mes and c.Anno = $list->Anno 
	and c.MedicamentoId = $list->MedicamentoId and c.RondaVerificacionId = $list->RondaVerificacionId)
	or
	(c.TipoMedicamentoId = $list->TipoMedicamentoId 
	and c.Mes = $list->Mes and c.Anno = $list->Anno)");
    }

    public function GetRondaById_MediamentoId($RondaVerificacionId, $MedicamentoId, $Fecha) {
        $this->db->objectBuilder()->rawQuery("SET @Contador4= 0;");
        return $this->db->objectBuilder()->rawQuery("SELECT r.RondaVerificacionId, if(m.TipoMedicamentoId = 1, 'ME', 'ANT') as Prefijo, DATE_FORMAT(r.Fecha, '%d%m%y') as OrdenProduccion, r.Fecha, 
        LPAD(IF(MONTH('$Fecha') = 2 and YEAR('$Fecha') = 2019, if(m.TipoMedicamentoId = 1, 54, 80) + GetLoteRondaVerificacion(m.TipoMedicamentoId, '$Fecha', m.MedicamentoId,$RondaVerificacionId ), GetLoteRondaVerificacion(m.TipoMedicamentoId, '$Fecha', m.MedicamentoId,$RondaVerificacionId )), 3, '000') as NumeroEnMes
        , u.NombreCompleto as NombreDireccionTecnica, u2.NombreCompleto as NombreACalidad
        , u3.NombreCompleto as NombreQFarmaceutico, u4.NombreCompleto as NombreAFarmacia
        , if(m.IsMiniBag and r.TipoRonda = 'Loteado', 'N/A', ifnull(convert(if(m.FechaLimiteUso2_8 LIKE '%Dia%', date_add(r.Fecha, interval SUBSTRING_INDEX(m.FechaLimiteUso2_8, ' ', 1) day), if(m.FechaLimiteUso2_8 LIKE '%Hora%', date_add(cast(concat(r.Fecha, ' ', '12:00:00') as datetime), interval SUBSTRING_INDEX(m.FechaLimiteUso2_8, ' ', 1) hour), null)), date), 'N/A')) as FechaLimiteUso2_8, 
        if(m.IsMiniBag and r.TipoRonda = 'Loteado', date_add(r.Fecha, interval 14 day),
        ifnull(convert(if(m.FechaLimiteUso20_25 LIKE '%Dia%', date_add(r.Fecha, interval SUBSTRING_INDEX(m.FechaLimiteUso20_25, ' ', 1) day), if(m.FechaLimiteUso20_25 LIKE '%Hora%', date_add(cast(concat(r.Fecha, ' ', '12:00:00') as datetime), interval SUBSTRING_INDEX(m.FechaLimiteUso20_25, ' ', 1) hour), null)), date), 'N/A')) as FechaLimiteUso20_25 
        , r.ACalidad, r.ACalidadId, r.AFarmacia, r.AFarmaciaId, r.DireccionTecnica, r.DireccionTecnicaId, r.QFarmaceutico, r.QFarmaceuticoId
        , u.Firma as FirmaDireccionTecnica, u4.Firma as FirmaAFarmacia, u2.Firma as FirmaACalidad, u3.Firma as FirmaQFarmaceutico
        FROM cm_rondaverificacion as r
        left join usuario as u on r.DireccionTecnicaId = u.UsuarioId and r.DireccionTecnica = true
        left join usuario as u2 on r.ACalidadId = u2.UsuarioId and r.ACalidad = true
        left join usuario as u3 on r.QFarmaceuticoId = u3.UsuarioId and r.QFarmaceutico = true
        left join usuario as u4 on r.AFarmaciaId = u4.UsuarioId and r.AFarmacia = true
        STRAIGHT_JOIN cm_medicamento as m on m.MedicamentoId = $MedicamentoId
        where r.RondaVerificacionId = $RondaVerificacionId;");
    }

    public function GetConsecutivos($RondaVerificacionId, $MedicamentoId, $Fecha) {
        return $this->db->objectBuilder()->rawQuery("SELECT LPAD(IF(MONTH('$Fecha') = 2 and YEAR('$Fecha') = 2019,if(m.TipoMedicamentoId = 1, 54, 80) + GetLoteRondaVerificacion_lite(m.TipoMedicamentoId, '$Fecha', $MedicamentoId,$RondaVerificacionId ), GetLoteRondaVerificacion_lite(m.TipoMedicamentoId, '$Fecha', $MedicamentoId,$RondaVerificacionId )), 3, '000') as NumeroEnMes 
from cm_medicamento as m where m.MedicamentoId = $MedicamentoId;");
    }

    public function GetRondaById($RondaVerificacionId) {
        return $this->db->objectBuilder()->rawQuery("SELECT RondaVerificacionId, DATE_FORMAT(Fecha, '%d%m%y') as OrdenProduccion, Fecha FROM cm_rondaverificacion where RondaVerificacionId = $RondaVerificacionId;");
    }

    public function GetAllRondas_Lite() {
//        return $this->db->objectBuilder()->rawQuery("select r.Fecha, r.RondaVerificacionId, m.Nombre as Medicamento, count(m.MedicamentoId) as Total from cm_rondaverificacion as r
//        inner join cm_detallerondaverificacion as dr on dr.RondaVerificacionId = r.RondaVerificacionId
//        inner join cm_medicamento as m on m.MedicamentoId = dr.MedicamentoId
//        group by m.Nombre
//        order by m.Nombre");
        return $this->db->objectBuilder()->rawQuery("select Fecha, RondaVerificacionId, TipoRonda, if(now() <= TIMESTAMP(Fecha,'18:00:00'), true, false) as Editable from cm_rondaverificacion
        order by RondaVerificacionId");
    }

    public function VerificarDia($RondaVerificacionId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * , if(now() <= TIMESTAMP(Fecha,'18:00:00'), true, false) as Editable FROM cm_rondaverificacion 
        where RondaVerificacionId = $RondaVerificacionId and now() >= Fecha and now() <= DATE_ADD(Fecha, INTERVAL 1 DAY);");
    }

    public function VerificarDiaBySector($Sector, $TipoMedicamento) {
        return $this->db->objectBuilder()->rawQuery("SELECT r.*, m.Nombre as TipoMedicamento FROM cm_rondaverificacion as r
        STRAIGHT_JOIN cm_detallerondaverificacion as dr on r.RondaVerificacionId = dr.RondaVerificacionId
        STRAIGHT_JOIN cm_medicamento as m on dr.MedicamentoId = m.MedicamentoId
        where dr.Sector = '$Sector' and now() >= r.Fecha and now() <= DATE_ADD(r.Fecha, INTERVAL 1 DAY) and m.TipoMedicamentoId = $TipoMedicamento;");
    }

    public function GetRondaByFecha($Fecha) {
        return $this->db->objectBuilder()->rawQuery("SELECT RondaVerificacionId, if(now() <= TIMESTAMP(Fecha,'18:00:00'), true, false) as Editable FROM cm_rondaverificacion where Fecha = '$Fecha'");
    }

    public function GetRondaByFecha_Estado($Fecha, $TipoRonda) {
        return $this->db->objectBuilder()->rawQuery("SELECT RondaVerificacionId, if(now() <= TIMESTAMP(Fecha,'18:00:00'), true, false) as Editable FROM cm_rondaverificacion where Fecha = '$Fecha' and TipoRonda = '$TipoRonda'");
    }

    public function GetRondaVerificacionById($RondaVerificacionId) {
        return $this->db->objectBuilder()->rawQuery("select *
                from cm_rondaverificacion where RondaVerificacionId = $RondaVerificacionId;");
    }

}
