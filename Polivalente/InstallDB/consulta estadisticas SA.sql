Select 
	vd.Descripcion as VariableD, sum(tabla.CantidadDesayuno) as TotalD
 FROM 
(SELECT  dhd.DId, 
(SELECT count(_dhd.DId) FROM sa_dhd as _dhd where _dhd.HDId = dhd.HDId and _dhd.DId = dhd.DId and _dhd.DId > 0) CantidadDesayuno,
dhd.AId, 
(SELECT count(_dhd.AId) FROM sa_dhd as _dhd where _dhd.HDId = dhd.HDId and _dhd.AId = dhd.AId and _dhd.AId > 0) CantidadAlmuerzo,
dhd.CId, 
(SELECT count(_dhd.CId) FROM sa_dhd as _dhd where _dhd.HDId = dhd.HDId and _dhd.CId = dhd.CId and _dhd.CId > 0) CantidadCena
from sa_dhd as dhd
inner join sa_hd as hd on dhd.HDId = hd.HDId
where 
dayofmonth(hd.Fecha) = 26 and month(hd.Fecha) = 06 and year(hd.Fecha) = 2020) as tabla

inner join sa_var as vd on tabla.DId = vd.VariableId
group by vd.Descripcion