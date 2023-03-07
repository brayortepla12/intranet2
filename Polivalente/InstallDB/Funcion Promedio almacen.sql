
USE `polivalente`;
DROP function IF EXISTS `GetMaxPedidoByPlantilla`;

DELIMITER $$
USE `polivalente`$$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetMaxPedidoByPlantilla`(UsuarioId int, ArticuloId int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select if( count(a.ArticuloId) > 0, sum(ip.CantidadSolicitada) / count(a.ArticuloId), 0)  from almacen_itempedido as ip 
		inner join almacen_articulo as a on ip.ArticuloId = a.ArticuloId
		inner join almacen_pedidoalmacen as pa on pa.PedidoAlmacenId = ip.PedidoAlmacenId
		where pa.SolicitanteId= UsuarioId
		and ip.ArticuloId = ArticuloId
		group by a.Nombre);
  RETURN dist;
END$$

DELIMITER ;
