
USE `polivalente`;
DROP function IF EXISTS `GetDateOrdenProduccion`;
DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetDateOrdenProduccion`(Fecha datetime, Intervalo varchar(10)) RETURNS datetime
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select  convert(if(Intervalo LIKE '%Dia%', date_add(Fecha, interval SUBSTRING_INDEX(Intervalo, ' ', 1) day), if(Intervalo LIKE '%Hora%', date_add(Fecha, interval SUBSTRING_INDEX(Intervalo, ' ', 1) hour), null)), datetime) as Fecha);
  RETURN dist;
END$$
DELIMITER ;
