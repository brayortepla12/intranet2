#ALTER TABLE `polivalente`.`almacen_relacioncosto` 
#ADD COLUMN `Limite` INT NULL AFTER `Cantidad`;
SET SQL_SAFE_UPDATES = 0;
start transaction;
	UPDATE almacen_relacioncosto as rc
	SET
	rc.Limite = GetMaxPedidoByPlantilla(rc.UsuarioId, rc.ArticuloId);
rollback;
commit;
select * from almacen_relacioncosto;
SET SQL_SAFE_UPDATES = 1;

