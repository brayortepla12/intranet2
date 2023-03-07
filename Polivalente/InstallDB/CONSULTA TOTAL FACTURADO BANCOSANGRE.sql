SELECT c.ClienteId, c.Nombre, sum(df.TPrecioFacturado) as 'Total Facturado' FROM facturacionbs.clientes as c
inner join facturas as f on c.ClienteId = f.ClienteId
inner join detalle_facturas as df on f.FacturaId = df.FacturaId
where MONTH(f.FechaEmision) = 12 and YEAR(f.FechaEmision) = 2019 and f.Estado = 'Activo'
group by c.Nombre;

#, sum(df.TPrecioFacturado) as 'Total Facturado'