USE `polivalente`;
DROP function IF EXISTS `GetLastReporteidByHojaVidaId`;

DELIMITER $$
USE `polivalente`$$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLastReporteidByHojaVidaId`(id int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select max(reporteid) from reporte where equipoid = id and EstadoReporte = 'Activo');
  RETURN dist;
END$$

DELIMITER ;

