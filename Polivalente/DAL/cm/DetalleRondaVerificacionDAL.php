<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class DetalleRondaVerificacionDAL {

    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();
       $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
       $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateDetalleRondaVerificacion($list) {
        $ids = $this->db->insertMulti("cm_DetalleRondaVerificacion", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function UpdateDetalleRondaVerificacion($list, $id) {
        $this->db->where('DetalleRondaVerificacionId', $id);
        if ($this->db->update('cm_DetalleRondaVerificacion', $list)) {
            return $list;
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    
    public function UpdateDMR($list, $id) {
        $this->db->where('DispositivoMedicoByRondaId', $id);
        if ($this->db->update('cm_dispositivomedicobyronda', $list)) {
            return $list;
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }    
    
    public function GetHistoricoByPacienteAndMedicamentoId($IdAfiliado, $MedicamentoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM polivalente.cm_detallerondaverificacion where IdAfiliado = '$IdAfiliado' 
        and MedicamentoId = $MedicamentoId and Estado = 'Activo' 
        order by DetalleRondaVerificacionId;");
    }
    
    public function GetDetalleRondaVerificacionByRondaVerificacionId($RondaVerificacionId) {
        return $this->db->objectBuilder()->rawQuery("SELECT DetalleRondaVerificacionId, Sector, RondaVerificacionId,PNombre,SNombre,PApellido,SApellido,IdAfiliado, VehiculoId,NoAdmision,EstadoPaciente 
                from cm_detallerondaverificacion where RondaVerificacionId = $RondaVerificacionId and Estado = 'Activo' group by PNombre;");
    }
    
    public function GetDetalleRondaVerificacionByRondaVerificacionId_Sector($RondaVerificacionId, $Sector, $Fecha) {
        return $this->db->objectBuilder()->rawQuery("SELECT dr.DetalleRondaVerificacionId, dr.Sector, dr.RondaVerificacionId,dr.PNombre,dr.SNombre,dr.PApellido,
            dr.SApellido,dr.IdAfiliado, dr.VehiculoId,dr.NoAdmision,dr.EstadoPaciente 
            from cm_detallerondaverificacion as dr
            STRAIGHT_JOIN cm_rondaverificacion as r on dr.RondaVerificacionId = r.RondaVerificacionId
where dr.RondaVerificacionId = $RondaVerificacionId and  Day(r.Fecha) = Day('$Fecha') and (r.TipoRonda = 'Loteado' or Sector = '$Sector') and dr.Estado = 'Activo' group by IdAfiliado;");
    }
    
    
    public function GetDetalleRondaVerificacionByRondaVerificacionId_preview($RondaVerificacionId, $MedicamentoId) {
        $this->db->objectBuilder()->rawQuery("SET @Contador=0;");
        return $this->db->objectBuilder()->rawQuery("SELECT LPAD(@Contador:=@Contador + 1, 3, '000') AS Item, dr.Sector, 
        concat(concat(dr.PNombre , ' ' , IFNULL(dr.SNombre, '')) , ' ' ,concat(dr.PApellido , ' ' , IFNULL(dr.SApellido, '')))as NombrePaciente, dr.IdAfiliado, dr.Verificado, dr.NoAdmision,
        m.Nombre as Medicamento,IF(m.CanRecostInLoteado = false and r.TipoRonda = 'Loteado', dr.dosis, m.Concentracion) as Concentracion, IF(m.CanRecostInLoteado = false and r.TipoRonda = 'Loteado', 'N/A', IFNULL(m.Recostituyente,'N/A')) as Recostituyente, 
        IF(m.CanRecostInLoteado = false and r.TipoRonda = 'Loteado', 'N/A', IF(m.VolumenReconstitucion is null or m.VolumenReconstitucion = 0 ,'N/A', m.VolumenReconstitucion)) as VolumenReconstitucion, 
        IF(m.CanRecostInLoteado = false and r.TipoRonda = 'Loteado', 'N/A', m.VolumenFinal) as VolumenFinal, 
       ROUND( IF(m.Concentracion <> 0 and m.VolumenFinal <> 0,((dr.Dosis * m.VolumenFinal) / m.Concentracion) / m.VolumenFinal, 0),2) as VialesAmpollasUtilizados, 
        ROUND(IF(m.CanRecostInLoteado = false and r.TipoRonda = 'Loteado' and m.MedicamentoId <> 30 /*clindamicina*/, 'N/A',IF(m.Concentracion <> 0,(dr.Dosis * m.VolumenFinal) / m.Concentracion, 0)),2) as VolumenATomar, 
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)),
        (select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        IF(dr.Volumen  <> 0 and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41),(select CONCAT(dm.Nombre , ' ', dm.Concentracion, ' ', dm.MedidaConcentracion) from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId), 
        'N/A' )
        ) as Vehiculo,
        IF(m.CanRecostInLoteado = false and r.TipoRonda = 'Loteado' and m.MedicamentoId <> 30 /*clindamicina*/, 'N/A',IF(m.Concentracion <> 0 and dr.Volumen  <> 0,dr.Volumen - ((dr.Dosis * m.VolumenFinal) / m.Concentracion), 'N/A')) as VolumenVehiculo, 
        dr.Dosis, ROUND(if((dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) , dr.Volumen, 
        IF(m.CanRecostInLoteado = false and r.TipoRonda = 'Loteado', 'N/A',IF(m.Concentracion <> 0,(dr.Dosis * m.VolumenFinal) / m.Concentracion, 0))), 2) as Volumen , 
        concat(concat(dr.Dosis , 'mg') , '/' , 
        concat(
        IF(dr.Volumen = 0, ROUND(IF(m.Concentracion <> 0,(dr.Dosis * m.VolumenFinal) / m.Concentracion, 0),2) , dr.Volumen) 
        , 
        'mL')) as ConcFinal, 
        dr.Cantidad,
        concat(concat(DATE_FORMAT(r.Fecha, '%d%m%y'), m.NombreAbreviado, dr.Dosis), '-', LPAD(@Contador, 3, '000')) as Lote, 
        dr.DetalleRondaVerificacionId,  
        dr.RondaVerificacionId
        from cm_detallerondaverificacion as dr
        STRAIGHT_JOIN cm_medicamento as m on m.MedicamentoId = dr.MedicamentoId 
        STRAIGHT_JOIN cm_rondaverificacion as r on r.RondaVerificacionId = dr.RondaVerificacionId       
        where dr.RondaVerificacionId = $RondaVerificacionId and dr.EstadoPaciente <> 'Suspender' and dr.MedicamentoId = $MedicamentoId and dr.Dosis > 0 and dr.Cantidad > 0 and dr.Estado = 'Activo';");
    }
    
    public function GetDetalleRondaVerificacionByRondaVerificacionId_productoInicial($RondaVerificacionId, $MedicamentoId) {
        // MEDICAMENTOS Y/O DISPOSITIVOS MEDICOS
        $this->db->rawQuery("SET @Contador=0;");
        return $this->db->objectBuilder()->rawQuery("SELECT LPAD(@Contador:=@Contador + 1, 3, '000') AS Item, 
        m.Nombre as DispositivoMedico,
        CONCAT(m.Concentracion, ' mg') as Concentracion, m.Laboratorio,'' as Lote, '' as FechaVencimiento, m.RegInvima,
        ROUND(Sum(IF(m.Concentracion <> 0,(dr.Dosis * m.VolumenFinal) / m.Concentracion /*volumen a tomar*/, 0) * (dr.Cantidad)) /  m.VolumenFinal , 2) as Cantidad, 0 as IsDP 
        from cm_detallerondaverificacion as dr
        STRAIGHT_JOIN cm_medicamento as m on m.MedicamentoId = dr.MedicamentoId 
        STRAIGHT_JOIN cm_rondaverificacion as r on r.RondaVerificacionId = dr.RondaVerificacionId  
        where dr.RondaVerificacionId = $RondaVerificacionId and dr.EstadoPaciente <> 'Suspender' and dr.MedicamentoId = $MedicamentoId and dr.Dosis > 0 and dr.Cantidad > 0 and dr.Estado = 'Activo'
        group by m.Nombre
        
        UNION
        
SELECT LPAD(@Contador:=@Contador + 1, 3, '000') AS Item, k.DispositivoMedico, k.Concentracion, k.Laboratorio, k.Lote, k.FechaVencimiento, k.RegistroInvima, sum(k.Cantidad), k.IsDP FROM


(SELECT '' AS Item, 
		IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10  and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)),
        (select CONCAT(dm.Nombre, ' ', dm.Concentracion, ' ', dm.MedidaConcentracion) from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        IF(dr.Volumen  <> 0,(select dm.OtroNombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId), 'N/A' )) as DispositivoMedico,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10  and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)),
        (select 'N/A' as Concentracion from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        (select CONCAT(dm.Concentracion, ' ', dm.MedidaConcentracion) from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as Concentracion,
		IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10  and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)),
        (select dm.Laboratorio from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        (select dm.Laboratorio from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as Laboratorio,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10  and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)),
        (select dm.Lote from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        (select dm.Lote from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as Lote,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10  and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)),
        (select dm.FechaVencimiento from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        (select dm.FechaVencimiento from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as FechaVencimiento,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10  and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)),
        (select dm.RegistroInvima from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        (select dm.RegistroInvima from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as RegistroInvima,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10  and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)), 
        1,
        (select 
        IF(sum(dr.Cantidad * dr.Volumen) > dm.Volumen, CEIL(sum(dr.Cantidad * dr.Volumen) / dm.Volumen), 1) 
        
        from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId )) as Cantidad, 0 as IsDP
        from cm_detallerondaverificacion as dr
        STRAIGHT_JOIN cm_medicamento as m on m.MedicamentoId = dr.MedicamentoId 
        STRAIGHT_JOIN cm_rondaverificacion as r on r.RondaVerificacionId = dr.RondaVerificacionId
        where dr.RondaVerificacionId = $RondaVerificacionId and dr.EstadoPaciente <> 'Suspender' and dr.Estado = 'Activo' and dr.MedicamentoId = $MedicamentoId and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)),
        (select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        IF(dr.Volumen  <> 0,(select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId), 'N/A' )) <> 'N/A'
        group by IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 ,
        (select dm.OtroNombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        IF(dr.Volumen  <> 0,(select dm.OtroNombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId), 'N/A' ))
        
        UNION
        
SELECT '' AS Item, 
		(select dm.OtroNombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = m.ReconstituyenteId) as DispositivoMedico,
        
        (select CONCAT(dm.Concentracion, ' ', dm.MedidaConcentracion) from cm_dispositivomedico as dm where dm.DispositivoMedicoId = m.ReconstituyenteId) as Concentracion,
		IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10  and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)),
        (select dm.Laboratorio from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        (select dm.Laboratorio from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as Laboratorio,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10  and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)),
        (select dm.Lote from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        (select dm.Lote from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as Lote,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10  and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)),
        (select dm.FechaVencimiento from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        (select dm.FechaVencimiento from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as FechaVencimiento,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10  and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)),
        (select dm.RegistroInvima from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 12) , 
        (select dm.RegistroInvima from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as RegistroInvima,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10  and (dr.MedicamentoId <> 18 and dr.MedicamentoId <> 16 and dr.MedicamentoId <> 41) and not (dr.VehiculoId IN (7,8,9,10,11)), 
        1,
        (select 
        IF(sum(dr.Cantidad * dr.Volumen) > dm.Volumen, CEIL(sum(dr.Cantidad * dr.Volumen) / dm.Volumen), 1) 
        
        from cm_dispositivomedico as dm where dm.DispositivoMedicoId = m.ReconstituyenteId )) as Cantidad, 0 as IsDP
        from cm_detallerondaverificacion as dr
        STRAIGHT_JOIN cm_medicamento as m on m.MedicamentoId = dr.MedicamentoId 
        STRAIGHT_JOIN cm_rondaverificacion as r on r.RondaVerificacionId = dr.RondaVerificacionId
        where dr.RondaVerificacionId = $RondaVerificacionId and dr.EstadoPaciente <> 'Suspender' and dr.Estado = 'Activo' and dr.MedicamentoId = $MedicamentoId and
        (select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = m.ReconstituyenteId) is not null
        group by 
        (select dm.OtroNombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = m.ReconstituyenteId)

        
        UNION
                
        SELECT '' AS Item, dm.OtroNombre as DispositivoMedico, 
        IFNULL(dm.Concentracion, 'N/A'), dm.Laboratorio, dm.Lote,dm.FechaVencimiento, dm.RegistroInvima, dmr.Cantidad, dmr.DispositivoMedicoByRondaId as IsDP
        from cm_DispositivoMedicoByRonda as dmr
        STRAIGHT_JOIN cm_dispositivomedico as dm on dm.DispositivoMedicoId = dmr.DispositivoMedicoId
        where dmr.RondaVerificacionId = $RondaVerificacionId and dmr.MedicamentoId = $MedicamentoId and dmr.Estado = 'Activo') as k group by k.DispositivoMedico order by Item");
    }
    
    
    
    public function GetDetalleRondaVerificacionByIdAfiliado($IdAfiliado) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM cm_detallerondaverificacion where IdAfiliado = '$IdAfiliado' order by DetalleRondaVerificacionId DESC;");
    }
    
    public function GetDetalleRondaVerificacionByIdAfiliado_MedicamentoId($IdAfiliado, $MedicamentoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT d.DetalleRondaVerificacionId, d.MedicamentoId, d.Cantidad, d.Dosis, d.VehiculoId ,d.Volumen FROM cm_detallerondaverificacion as d 
        where d.IdAfiliado = '$IdAfiliado' and d.MedicamentoId = $MedicamentoId order by d.DetalleRondaVerificacionId DESC;");
    }
    
    public function GetDetalleRondaVerificacionByIdAfiliado_MedicamentoIdFecha($IdAfiliado, $MedicamentoId, $Fecha) {
        return $this->db->objectBuilder()->rawQuery("SELECT d.DetalleRondaVerificacionId, d.MedicamentoId, d.Cantidad, d.Dosis, d.VehiculoId, 
            CONCAT(dm.Nombre, ' ', dm.Concentracion, ' ', dm.MedidaConcentracion ) as Vehiculo ,d.Volumen 
            FROM cm_detallerondaverificacion as d 
            STRAIGHT_JOIN cm_rondaverificacion as r on d.RondaVerificacionId = r.RondaVerificacionId
            STRAIGHT_JOIN cm_dispositivomedico as dm on dm.DispositivoMedicoId = d.VehiculoId
        where d.IdAfiliado = '$IdAfiliado' and d.MedicamentoId = $MedicamentoId and (r.Fecha = '$Fecha') and d.Estado = 'Activo' order by d.DetalleRondaVerificacionId DESC;");
    }
    
    public function GetCantidad($IdAfiliado, $RondaVerificacionId, $TipoMedicamentoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT count(*) as Total
        FROM cm_detallerondaverificacion as d
        STRAIGHT_JOIN cm_medicamento as m on m.MedicamentoId = d.MedicamentoId
        where d.IdAfiliado = '$IdAfiliado' and d.RondaVerificacionId = $RondaVerificacionId and m.TipoMedicamentoId = $TipoMedicamentoId and d.Estado = 'Activo';");
    }
    
    public function GetDetalleRondaVerificacionByIdAfiliado_MedicamentoId_Ronda($IdAfiliado, $MedicamentoId,$RondaVerificacionId) {
        return $this->db->objectBuilder()->rawQuery("SELECT d.MedicamentoId, d.Cantidad, d.VehiculoId, d.Dosis,d.Volumen FROM cm_detallerondaverificacion as d 
        where d.IdAfiliado = '$IdAfiliado' and d.MedicamentoId = $MedicamentoId and d.RondaVerificacionId = $RondaVerificacionId;");
    }
    
    
}


