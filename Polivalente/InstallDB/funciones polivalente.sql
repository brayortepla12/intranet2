DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetDateOrdenProduccion`(Fecha datetime, Intervalo varchar(10)) RETURNS datetime
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select  convert(if(Intervalo LIKE '%Dia%', date_add(Fecha, interval SUBSTRING_INDEX(Intervalo, ' ', 1) day), if(Intervalo LIKE '%Hora%', date_add(Fecha, interval SUBSTRING_INDEX(Intervalo, ' ', 1) hour), null)), datetime) as Fecha);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteid`(id int, Anno int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where (equipoid = id and (year(Fecha) = Anno - 1 or year(Fecha) = Anno )) and TipoServicio <> 'CALIBRACION');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteidByHojaVidaId`(id int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where equipoid = id and EstadoReporte = 'Activo');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteidByHojaVidaIdCalibracion`(id int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where equipoid = id and TipoServicio = 'CALIBRACION');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteidSistemas`(id int, Anno int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from sistemas_reporte where (equipoid = id and (year(Fecha) = Anno - 1 or year(Fecha) = Anno )) and TipoServicio <> 'CALIBRACION');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLoteRondaVerificacion`(TipoMedicamentoId int, Fecha date, MedicamentoId int, RondaVerificacionId int) RETURNS varchar(4) CHARSET latin1
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET @ContadorMedicamentos=0;
  SET dist = (
	select b.Item from (select LPAD(@ContadorMedicamentos:=@ContadorMedicamentos + 1, 3, '000') AS Item, a.DetalleRondaVerificacionId, a.MedicamentoId, a.RondaVerificacionId, a.Orden from 
	(select dr.DetalleRondaVerificacionId, dr.MedicamentoId, r.RondaVerificacionId, m.Orden
	from 
	cm_detallerondaverificacion as dr 
	inner join cm_rondaverificacion as r on r.RondaVerificacionId = dr.RondaVerificacionId
	inner join cm_medicamento as m on dr.MedicamentoId = m.MedicamentoId
	where month(r.Fecha) = month(Fecha) and m.TipoMedicamentoId = TipoMedicamentoId group by dr.RondaVerificacionId, m.MedicamentoId
	) as a) as b where b.MedicamentoId = MedicamentoId and b.RondaVerificacionId = RondaVerificacionId
  );
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetMaxPedidoByPlantilla`(UsuarioId int, ArticuloId int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(ip.CantidadSolicitada)   from almacen_itempedido as ip 
		inner join almacen_articulo as a on ip.ArticuloId = a.ArticuloId
		inner join almacen_pedidoalmacen as pa on pa.PedidoAlmacenId = ip.PedidoAlmacenId
		where pa.SolicitanteId= UsuarioId
		and ip.ArticuloId = ArticuloId
		group by a.Nombre);
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
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

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `HaveParto`(MaternaId int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (SELECT count(*) FROM polivalente.tm_materna as m
	inner join polivalente.tm_evento as e on e.MaternaId = m.MaternaId
	where (e.TipoEvento = 'Parto' or e.TipoEvento = 'Parto Externo') and m.MaternaId= MaternaId);
  RETURN dist;
END$$
DELIMITER ;
