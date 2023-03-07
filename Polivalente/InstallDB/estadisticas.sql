select eq.SedeId, eq.ServicioId, eq.Nombre, eq.Total_Equipos, 
ifnull(rep.Total_Reportes, 0) 'Total_Reportes', 
ifnull(ron.Total_Ronda, 0) 'Total_Ronda', 
ifnull(ra.Total_RondaAmbiental, 0) 'Total_RondaAmbiental',
ifnull(shv.Total_HojaVidaSistema, 0) 'Total_HojaVidaSistema',
ifnull(pv.Total_PedidoAlmacen, 0) 'Total_PedidoAlmacen',
ifnull(pr.Total_PedidoRepuestos, 0) 'Total_PedidoRepuestos',
ifnull(rc.Total_RelacionCostos, 0) 'Total_RelacionCostos',
ifnull(f.Total_FormatoServicio, 0) 'Total_FormatoServicio',
ifnull(sue.Total_ServicioUsuario, 0) 'Total_ServicioUsuario'

from (
 SELECT s.SedeId, s.ServicioId, s.Nombre, count(h.HojaVidaId) 'Total_Equipos'  
FROM polivalente.servicio as s
left join hojavida as h on s.ServicioId = h.ServicioId
group by s.Nombre) eq 
left outer join (
SELECT s.SedeId, s.ServicioId, s.Nombre, count(r.ReporteId) 'Total_Reportes' 
FROM polivalente.servicio as s
left join reporte as r on s.ServicioId = r.ServicioId
group by s.Nombre) rep on eq.ServicioId = rep.ServicioId

left outer join (
SELECT s.SedeId, s.ServicioId, s.Nombre, count(r.RondaId) 'Total_Ronda' 
FROM polivalente.servicio as s
left join ronda as r on s.ServicioId = r.ServicioId
group by s.Nombre) ron on eq.ServicioId = ron.ServicioId

left outer join (
SELECT s.SedeId, s.ServicioId, s.Nombre, count(r.RondaAmbientalId) 'Total_RondaAmbiental' 
FROM polivalente.servicio as s
left join rondaambiental as r on s.ServicioId = r.ServicioId
group by s.Nombre) ra on eq.ServicioId = ra.ServicioId

left outer join (
SELECT s.SedeId, s.ServicioId, s.Nombre, count(sh.HojaVidaId) 'Total_HojaVidaSistema' 
FROM polivalente.servicio as s
left join sistemas_hojavida as sh on s.ServicioId = sh.ServicioId
group by s.Nombre) shv on eq.ServicioId = shv.ServicioId

left outer join (
SELECT s.SedeId, s.ServicioId, s.Nombre, count(p.PedidoAlmacenId) 'Total_PedidoAlmacen' 
FROM polivalente.servicio as s
left join almacen_pedidoalmacen as p on s.ServicioId = p.ServicioId
group by s.Nombre) pv on eq.ServicioId = pv.ServicioId

left outer join (
SELECT s.SedeId, s.ServicioId, s.Nombre, count(p.PedidoAlmacenId) 'Total_PedidoRepuestos' 
FROM polivalente.servicio as s
left join pedidoalmacen as p on s.ServicioId = p.ServicioId
group by s.Nombre) pr on eq.ServicioId = pr.ServicioId

left outer join (
SELECT s.SedeId, s.ServicioId, s.Nombre, count(rc.RelacionCostoId) 'Total_RelacionCostos' 
FROM polivalente.servicio as s
left join almacen_relacioncosto as rc on s.ServicioId = rc.ServicioId
group by s.Nombre) rc on eq.ServicioId = rc.ServicioId


left outer join (
SELECT s.SedeId, s.ServicioId, s.Nombre, count(f.FormatoId) 'Total_FormatoServicio' 
FROM polivalente.servicio as s
left join formatoservicio as f on s.ServicioId = f.ServicioId
group by s.Nombre) f on eq.ServicioId = f.ServicioId

left outer join (
SELECT s.SedeId, s.ServicioId, s.Nombre, count(su.ServicioUsuarioId) 'Total_ServicioUsuario' 
FROM polivalente.servicio as s
left join serviciousuario as su on s.ServicioId = su.ServicioId
group by s.Nombre) sue on eq.ServicioId = sue.ServicioId;


