select s.SECTOR, t.* from sa_sector as s
left join (
select h.SECTOR as '_Sector', 
	ContarComidasPorHDPorDia(h.SECTOR, 23, 10, 2020) as '23',
	ContarComidasPorHDPorDia(h.SECTOR, 24, 10, 2020) as '24',
	ContarComidasPorHDPorDia(h.SECTOR, 25, 10, 2020) as '25',
	ContarComidasPorHDPorDia(h.SECTOR, 26, 10, 2020) as '26',
	ContarComidasPorHDPorMes(h.SECTOR, 10, 2020) as Total
from sa_hd as h
inner join sa_sector as s on s.SECTOR = h.SECTOR
inner join sa_empresasector as es on es.SectorId = s.SectorId
where month(h.Fecha) = 10 and year(h.Fecha) = 2020 and es.EmpresaId = 1
group by h.SECTOR
) as t on s.SECTOR = t._Sector 
order by SECTOR