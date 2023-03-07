select a.ArticuloId, a.Nombre, sum(ip.CantidadSolicitada) as TotalSolicitado  from almacen_itempedido as ip 
inner join almacen_articulo as a on ip.ArticuloId = a.ArticuloId
inner join almacen_pedidoalmacen as pa on pa.PedidoAlmacenId = ip.PedidoAlmacenId
where pa.SolicitanteId= 77 and pa.Estado = 'Activo'  
and MONTH(pa.FechaSolicitud) = 10 
and ip.CantidadSolicitada > 0
and ip.ArticuloId = 22
group by a.Nombre;