start transaction;
INSERT INTO almacen_relacioncosto (
`ArticuloId`,
`Cantidad`,
`SedeId`,
`DiasConsumo`,
`ServicioId`,
`UsuarioId`,
Limite,
`Estado`,
`CreatedBy`)

SELECT rc.ArticuloId, rc.Cantidad, rc.SedeId, rc.DiasConsumo, rc.ServicioId, 36/* Usuario Destino*/, rc.Limite , rc.Estado, rc.CreatedBy 
FROM almacen_relacioncosto as rc 
inner join almacen_articulo as a on rc.ArticuloId = a.ArticuloId
where rc.UsuarioId=25/* Usuario Origen*/ and (rc.ServicioId = 16 or rc.ServicioId = 8);/* Servicio a clonar*/ #and a.ArticuloPara= 'Almacen' /*Tipo de articulo a clonar*/;

rollback;
commit;
/* anyela */