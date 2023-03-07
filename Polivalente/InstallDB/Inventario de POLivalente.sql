select h.HojaVidaId, h.Equipo, h.Marca, h.Modelo, h.Serie, ser.Nombre as Servicio, h.Ubicacion from hojavida as h 
inner join servicio as ser on h.ServicioId = ser.ServicioId
where h.Estado <> 'Inactivo' and h.SedeId <> 2 order by h.SedeId, ser.Nombre, h.Ubicacion, h.Equipo