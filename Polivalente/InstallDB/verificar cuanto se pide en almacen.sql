SELECT p.FechaEntrega, ip.CantidadEntregada, ip.ArticuloId  FROM polivalente.almacen_pedidoalmacen as p
inner join almacen_itempedido as ip on p.PedidoAlmacenId = ip.PedidoAlmacenId
where ip.ArticuloId = 226 and p.FechaEntrega >= '2018-07-01' and p.FechaEntrega <= '2018-12-31' and ip.CantidadEntregada <> 0 and p.ServicioId = 185 order by p.FechaEntrega; 