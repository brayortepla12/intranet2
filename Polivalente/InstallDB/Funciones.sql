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
  SET dist = (select max(reporteid) from reporte where equipoid = id and estado = 'Activo');
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
