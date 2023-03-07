SELECT v.Descripcion, v.Abrv, sum(t.Cantidad) as Total FROM (
SELECT  dhd.DId as VarId, 
(SELECT count(_dhd.DId) FROM sa_dhd as _dhd where _dhd.HDId = dhd.HDId and _dhd.DId = dhd.DId and _dhd.DId > 0) Cantidad
from sa_dhd as dhd
inner join sa_hd as hd on dhd.HDId = hd.HDId
where 
dayofmonth(hd.Fecha) = 26 and month(hd.Fecha) = 06 and year(hd.Fecha) = 2020 and hd.CDesayuno

UNION

SELECT  dhd.AId as VarId, 
(SELECT count(_dhd.AId) FROM sa_dhd as _dhd where _dhd.HDId = dhd.HDId and _dhd.AId = dhd.AId and _dhd.AId > 0) Cantidad
from sa_dhd as dhd
inner join sa_hd as hd on dhd.HDId = hd.HDId
where 
dayofmonth(hd.Fecha) = 26 and month(hd.Fecha) = 06 and year(hd.Fecha) = 2020 and hd.CAlmuerzo

UNION

SELECT  dhd.CId as VarId, 
(SELECT count(_dhd.CId) FROM sa_dhd as _dhd where _dhd.HDId = dhd.HDId and _dhd.CId = dhd.CId and _dhd.CId > 0) Cantidad
from sa_dhd as dhd
inner join sa_hd as hd on dhd.HDId = hd.HDId
where 
dayofmonth(hd.Fecha) = 26 and month(hd.Fecha) = 06 and year(hd.Fecha) = 2020 and hd.CCena

) as t
inner join sa_var as v on t.VarId = v.VariableId
group by v.Descripcion
