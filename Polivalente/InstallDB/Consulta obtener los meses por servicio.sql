select tabla.* from (select dc.*, rdc.ReporteId from sistemas_detallecronograma as dc 
inner join sistemas_hojavida as h on h.ServicioId = dc.ServicioId
left join sistemas_reportedcronograma as rdc on dc.DetalleCronogramaId = rdc.DetalleCronogramaId
left join sistemas_reporte as r on h.HojaVidaId = r.EquipoId and rdc.ReporteId = r.ReporteId
where h.HojaVidaId = 341 and r.ReporteId is null order by dc.Mes) tabla where tabla.ReporteId is null limit 1