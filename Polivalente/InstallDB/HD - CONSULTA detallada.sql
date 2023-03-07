SELECT t.*, t.Desayuno + t.MM + t.Almuerzo + t.MT + t.Cena + t.MN  as TOTAL FROM (
SELECT v.Abrv, v.Descripcion,
	(
		SELECT count(dhd.DesayunoId) FROM sa_dhd as dhd
        INNER JOIN sa_hd as h on h.HDId = dhd.HDId
        INNER JOIN sa_sector as s on s.SECTOR = h.SECTOR
        INNER JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = 1
        where dhd.DesayunoId = v.VariableId 
        and ((dayofmonth(dhd.FIHD) = 26 and 26 <> 'TODOS') || '' = 'TODOS') and month(dhd.FIHD) = 10 and year(dhd.FIHD) = 2020
    ) Desayuno,
    (
		SELECT count(dhd.MMId) FROM sa_dhd as dhd 
        INNER JOIN sa_hd as h on h.HDId = dhd.HDId
        INNER JOIN sa_sector as s on s.SECTOR = h.SECTOR
        INNER JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = 1
        where dhd.MMId = v.VariableId 
        and ((dayofmonth(dhd.FIHD) = 26 and 26 <> 'TODOS') || 'TODOS' = 'TODOS') and month(dhd.FIHD) = 10 and year(dhd.FIHD) = 2020
    ) MM,
    (
		SELECT count(dhd.AlmuerzoId) FROM sa_dhd as dhd 
        INNER JOIN sa_hd as h on h.HDId = dhd.HDId
        INNER JOIN sa_sector as s on s.SECTOR = h.SECTOR
        INNER JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = 1
        where dhd.AlmuerzoId = v.VariableId 
        and ((dayofmonth(dhd.FIHD) = 26 and 26 <> 'TODOS') || 'TODOS' = 'TODOS') and month(dhd.FIHD) = 10 and year(dhd.FIHD) = 2020
    ) Almuerzo,
    (
		SELECT count(dhd.MTId) FROM sa_dhd as dhd 
        INNER JOIN sa_hd as h on h.HDId = dhd.HDId
        INNER JOIN sa_sector as s on s.SECTOR = h.SECTOR
        INNER JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = 1
        where dhd.MTId = v.VariableId 
        and ((dayofmonth(dhd.FIHD) = 26 and 26 <> 'TODOS') || 'TODOS' = 'TODOS') and month(dhd.FIHD) = 10 and year(dhd.FIHD) = 2020
    ) MT,
    (
		SELECT count(dhd.CenaId) FROM sa_dhd as dhd 
        INNER JOIN sa_hd as h on h.HDId = dhd.HDId
        INNER JOIN sa_sector as s on s.SECTOR = h.SECTOR
        INNER JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = 1
        where dhd.CenaId = v.VariableId
        and ((dayofmonth(dhd.FIHD) = 26 and 26 <> 'TODOS') || 'TODOS' = 'TODOS') and month(dhd.FIHD) = 10 and year(dhd.FIHD) = 2020
    ) Cena,
    (
		SELECT count(dhd.MNId) FROM sa_dhd as dhd 
        INNER JOIN sa_hd as h on h.HDId = dhd.HDId
        INNER JOIN sa_sector as s on s.SECTOR = h.SECTOR
        INNER JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = 1
        where dhd.MNId = v.VariableId 
        and ((dayofmonth(dhd.FIHD) = 26 and 26 <> 'TODOS') || 'TODOS' = 'TODOS') and month(dhd.FIHD) = 10 and year(dhd.FIHD) = 2020
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
        INNER JOIN sa_hd as h on h.HDId = dhd.HDId
        INNER JOIN sa_sector as s on s.SECTOR = h.SECTOR
        INNER JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = 1
        where dhd.DesayunoId = v.VariableId 
        and ((dayofmonth(dhd.FIHD) = 26 and 26 <> 'TODOS') || '' = 'TODOS') and month(dhd.FIHD) = 10 and year(dhd.FIHD) = 2020
    ) Desayuno,
    (
		SELECT count(dhd.MMId) FROM sa_dhd as dhd 
        INNER JOIN sa_hd as h on h.HDId = dhd.HDId
        INNER JOIN sa_sector as s on s.SECTOR = h.SECTOR
        INNER JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = 1
        where dhd.MMId = v.VariableId 
        and ((dayofmonth(dhd.FIHD) = 26 and 26 <> 'TODOS') || 'TODOS' = 'TODOS') and month(dhd.FIHD) = 10 and year(dhd.FIHD) = 2020
    ) MM,
    (
		SELECT count(dhd.AlmuerzoId) FROM sa_dhd as dhd 
        INNER JOIN sa_hd as h on h.HDId = dhd.HDId
        INNER JOIN sa_sector as s on s.SECTOR = h.SECTOR
        INNER JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = 1
        where dhd.AlmuerzoId = v.VariableId 
        and ((dayofmonth(dhd.FIHD) = 26 and 26 <> 'TODOS') || 'TODOS' = 'TODOS') and month(dhd.FIHD) = 10 and year(dhd.FIHD) = 2020
    ) Almuerzo,
    (
		SELECT count(dhd.MTId) FROM sa_dhd as dhd 
        INNER JOIN sa_hd as h on h.HDId = dhd.HDId
        INNER JOIN sa_sector as s on s.SECTOR = h.SECTOR
        INNER JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = 1
        where dhd.MTId = v.VariableId 
        and ((dayofmonth(dhd.FIHD) = 26 and 26 <> 'TODOS') || 'TODOS' = 'TODOS') and month(dhd.FIHD) = 10 and year(dhd.FIHD) = 2020
    ) MT,
    (
		SELECT count(dhd.CenaId) FROM sa_dhd as dhd 
        INNER JOIN sa_hd as h on h.HDId = dhd.HDId
        INNER JOIN sa_sector as s on s.SECTOR = h.SECTOR
        INNER JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = 1
        where dhd.CenaId = v.VariableId
        and ((dayofmonth(dhd.FIHD) = 26 and 26 <> 'TODOS') || 'TODOS' = 'TODOS') and month(dhd.FIHD) = 10 and year(dhd.FIHD) = 2020
    ) Cena,
    (
		SELECT count(dhd.MNId) FROM sa_dhd as dhd 
        INNER JOIN sa_hd as h on h.HDId = dhd.HDId
        INNER JOIN sa_sector as s on s.SECTOR = h.SECTOR
        INNER JOIN sa_empresasector as es on es.SectorId = s.SectorId and es.EmpresaId = 1
        where dhd.MNId = v.VariableId 
        and ((dayofmonth(dhd.FIHD) = 26 and 26 <> 'TODOS') || 'TODOS' = 'TODOS') and month(dhd.FIHD) = 10 and year(dhd.FIHD) = 2020
    ) MN
 from sa_var as v
 order by v.Descripcion
 ) as t