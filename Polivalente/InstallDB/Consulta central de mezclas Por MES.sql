
select r.Fecha, m.Nombre, m.Concentracion, m.NombreAbreviado, sum(dr.Cantidad) as CantidadAPreparar, 

ROUND(Sum(IF(m.Concentracion <> 0,(dr.Dosis * m.VolumenFinal) / m.Concentracion /*volumen a tomar*/, 0) * (dr.Cantidad)) /  m.VolumenFinal , 2) as CantidadUtilizada
, m.CodigoKrystalos
 from cm_detallerondaverificacion as dr
inner join cm_rondaverificacion as r on dr.RondaVerificacionId = r.RondaVerificacionId
inner join cm_medicamento as m on m.MedicamentoId = dr.MedicamentoId
where MONTH(r.Fecha) = 8 and YEAR(r.Fecha) = 2019 and m.IsLoteado = 0 and m.IsInRonda = 1 and dr.Estado = 'Activo' and dr.EstadoPaciente <> 'Suspender'

group by m.Nombre, r.Fecha order by r.Fecha, m.Nombre

