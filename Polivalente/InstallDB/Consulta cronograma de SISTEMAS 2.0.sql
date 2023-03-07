select s.Nombre as Servicio, s.ServicioId, 
dc1.Mes as '1', GetReporteIdByDetalleCronograma(dc1.DetalleCronogramaId) as 'r1',
dc2.Mes as '2', GetReporteIdByDetalleCronograma(dc2.DetalleCronogramaId) as 'r2', 
dc3.Mes as '3', GetReporteIdByDetalleCronograma(dc3.DetalleCronogramaId) as 'r3', 
dc4.Mes as '4', GetReporteIdByDetalleCronograma(dc4.DetalleCronogramaId) as 'r4', 
dc5.Mes as '5', GetReporteIdByDetalleCronograma(dc5.DetalleCronogramaId) as 'r5', 
dc6.Mes as '6', GetReporteIdByDetalleCronograma(dc6.DetalleCronogramaId) as 'r6', 
dc7.Mes as '7', GetReporteIdByDetalleCronograma(dc7.DetalleCronogramaId) as 'r7', 
dc8.Mes as '8', GetReporteIdByDetalleCronograma(dc8.DetalleCronogramaId) as 'r8', 
dc9.Mes as '9', GetReporteIdByDetalleCronograma(dc9.DetalleCronogramaId) as 'r9', 
dc10.Mes as '10', GetReporteIdByDetalleCronograma(dc10.DetalleCronogramaId) as 'r10', 
dc11.Mes as '11', GetReporteIdByDetalleCronograma(dc11.DetalleCronogramaId) as 'r11', 
dc12.Mes as '12', GetReporteIdByDetalleCronograma(dc12.DetalleCronogramaId) as 'r12'
from servicio as s
inner join sistemas_detallecronograma as dc on s.ServicioId = dc.ServicioId
left join sistemas_detallecronograma as dc1 on s.ServicioId = dc1.ServicioId and dc1.Mes = 1
left join sistemas_detallecronograma as dc2 on s.ServicioId = dc2.ServicioId and dc2.Mes = 2
left join sistemas_detallecronograma as dc3 on s.ServicioId = dc3.ServicioId and dc3.Mes = 3
left join sistemas_detallecronograma as dc4 on s.ServicioId = dc4.ServicioId and dc4.Mes = 4
left join sistemas_detallecronograma as dc5 on s.ServicioId = dc5.ServicioId and dc5.Mes = 5
left join sistemas_detallecronograma as dc6 on s.ServicioId = dc6.ServicioId and dc6.Mes = 6
left join sistemas_detallecronograma as dc7 on s.ServicioId = dc7.ServicioId and dc7.Mes = 7
left join sistemas_detallecronograma as dc8 on s.ServicioId = dc8.ServicioId and dc8.Mes = 8
left join sistemas_detallecronograma as dc9 on s.ServicioId = dc9.ServicioId and dc9.Mes = 9
left join sistemas_detallecronograma as dc10 on s.ServicioId = dc10.ServicioId and dc10.Mes = 10
left join sistemas_detallecronograma as dc11 on s.ServicioId = dc11.ServicioId and dc11.Mes = 11
left join sistemas_detallecronograma as dc12 on s.ServicioId = dc12.ServicioId and dc12.Mes = 12
inner join sistemas_cronograma as c on c.CronogramaId = dc.CronogramaId
where c.Vigencia = 2019 group by s.ServicioId order by s.Nombre