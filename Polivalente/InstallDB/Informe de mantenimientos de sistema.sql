SELECT  h.HojaVidaId, s.Nombre as Sede, ser.Nombre as Servicio , h.Ubicacion,h.Nombre, h.NSerial, h.TipoArticulo, r.ReporteId,r.Fecha as FechaUltimoReporte,r.TipoServicio as TipoReporte FROM sistemas_hojavida as h
left join sistemas_reporte as r on r.ReporteId = GetLastReporteidSistemas(h.HojaVidaId, 2018)
inner join sede as s on s.SedeId = h.SedeId
inner join servicio as ser on ser.ServicioId = h.ServicioId
where (r.TipoServicio = 'CORRECTIVO' OR r.TipoServicio = 'PREVENTIVO') and h.TipoArticulo <> 'Impresora';