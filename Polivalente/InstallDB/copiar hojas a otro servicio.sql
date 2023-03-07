start transaction;
INSERT INTO hojavida (
`SedeId`,
`ServicioId`,
`FrecuenciaMantenimientoId`,
`Ubicacion`,
`Equipo`,
`Marca`,
`Modelo`,
`Serie`,
`Inventario`,
`RegSanitario`,
`FechaAdquisicion`,
`Garantia`,
`ProveedorId`,
`Presion`,
`Peso`,
`Temperatura`,
`Capacidad`,
`TipoRiesgo`,
`Voltaje`,
`Amperaje`,
`Frecuencia`,
`Potencia`,
`RecomendacioneFabricante`,
`Accesorios`,
`Foto`,
`Estado`,
`FechaInstalacion`,
`FechaCalibracion`,
`FrecuenciaCalibracionId`,
`CreatedBy`)

SELECT  3,3,h.FrecuenciaMantenimientoId, h.Ubicacion, h.Equipo, h.Marca, h.Modelo, 
h.Serie, h.Inventario, h.RegSanitario, h.FechaAdquisicion,
 h.Garantia, h.ProveedorId, h.Presion, h.Peso, h.Temperatura,
 h.Capacidad, h.TipoRiesgo, h.Voltaje, h.Amperaje, h.Frecuencia, h.Potencia, h.RecomendacioneFabricante, h.Accesorios, h.Foto, h.Estado, h.FechaInstalacion, h.FechaCalibracion, h.FrecuenciaCalibracionId
 , h.CreatedBy
FROM    hojavida as h
WHERE   h.ServicioId = 53;
rollback;