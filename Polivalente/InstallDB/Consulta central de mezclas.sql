SET @Contador=0;
SELECT LPAD(@Contador:=@Contador + 1, 3, '000') AS Item, dr.Sector, 
concat(concat(dr.PNombre , ' ' , dr.SNombre) , ' ' ,concat(dr.PApellido , ' ' , dr.SApellido))as NombrePaciente, 
m.Nombre as Medicamento, m.Concentracion, IFNULL(m.Recostituyente,'N/A') as Recostituyente, 
IFNULL(m.VolumenReconstitucion,'N/A') as VolumenReconstitucion, m.VolumenFinal, 
IF(m.Concentracion <> 0 and m.VolumenFinal <> 0,((dr.Dosis * m.VolumenFinal) / m.Concentracion) / m.VolumenFinal, 0) as VialesAmpollasUtilizados, 
IF(m.Concentracion <> 0,(dr.Dosis * m.VolumenFinal) / m.Concentracion, 0) as VolumenATomar, 
IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 ,
(select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 5) , 
IF(dr.Volumen  > 0,(select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId), 'N/A' )) as Vehiculo,
IF(m.Concentracion <> 0 and dr.Volumen  <> 0,dr.Volumen - ((dr.Dosis * m.VolumenFinal) / m.Concentracion), 'N/A') as VolumenVehiculo, 
dr.Dosis, dr.Volumen, 
concat(concat(dr.Dosis , 'mg') , '/' , 
concat(
IF(dr.Volumen = 0, IF(m.Concentracion <> 0,(dr.Dosis * m.VolumenFinal) / m.Concentracion, 0) , dr.Volumen) 
, 
'mL')) as ConcFinal, 
dr.Cantidad,
concat(concat(DATE_FORMAT(r.Fecha, '%d%m%y'), m.NombreAbreviado, dr.Dosis), '-', LPAD(@Contador, 3, '000')) as Lote, 
dr.DetalleRondaVerificacionId,  dr.Estado, dr.EstadoPaciente, dr.IdAfiliado, dr.MedicamentoId, dr.NoAdmision, 
dr.RondaVerificacionId
from cm_detallerondaverificacion as dr
inner join cm_medicamento as m on m.MedicamentoId = dr.MedicamentoId 
inner join cm_rondaverificacion as r on r.RondaVerificacionId = dr.RondaVerificacionId  
where dr.RondaVerificacionId = 2 and dr.EstadoPaciente <> 'Suspender' and dr.MedicamentoId = 1;