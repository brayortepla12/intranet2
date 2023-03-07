DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteid`(id int, Anno int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where (equipoid = id and (year(Fecha) = Anno - 1 or year(Fecha) = Anno )) and (TipoServicio <> 'CALIBRACION' and TipoServicio <> 'CALIBRACIÓN') and EstadoReporte <> 'Inactivo');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteidByHojaVidaId`(id int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where equipoid = id and (TipoServicio <> 'CALIBRACION' and TipoServicio <> 'CALIBRACIÓN') and EstadoReporte <> 'Inactivo');
  RETURN dist;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteidByHojaVidaIdCalibracion`(id int, Anno int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where (equipoid = id and (year(Fecha) = Anno - 1 or year(Fecha) = Anno )) and (TipoServicio = 'CALIBRACION' || TipoServicio = 'CALIBRACIÓN') and EstadoReporte <> 'Inactivo');
  RETURN dist;
END$$
DELIMITER ;
