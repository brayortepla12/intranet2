select h.Nombre,h.NSerial,h.TipoArticulo,h.Contador,ser.Nombre as SERVICIO, h.Ubicacion as UBICACION from sistemas_hojavida as h
inner join servicio as ser on h.ServicioId = ser.ServicioId
where h.SedeId <> 3
order by h.SedeId, ser.Nombre 