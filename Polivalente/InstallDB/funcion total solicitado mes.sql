USE `polivalente`;
DROP function IF EXISTS `GetTotalSolicitadoByMes`;

DELIMITER $$
USE `polivalente`$$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetTotalSolicitadoByMes`(UsuarioId int, ArticuloId int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select sum(ip.CantidadSolicitada) from almacen_itempedido as ip 
	inner join almacen_articulo as a on ip.ArticuloId = a.ArticuloId
	inner join almacen_pedidoalmacen as pa on pa.PedidoAlmacenId = ip.PedidoAlmacenId
	where pa.SolicitanteId= UsuarioId and pa.Estado <> 'Rechazar'  
	and MONTH(pa.FechaSolicitud) = MONTH(now())
	and ip.CantidadSolicitada > 0
	and ip.ArticuloId = ArticuloId
	group by a.Nombre);
  RETURN dist;
END$$

DELIMITER ;