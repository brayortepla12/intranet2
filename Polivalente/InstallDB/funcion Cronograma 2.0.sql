

DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetReporteIdByDetalleCronograma`(_DetalleCronograma int, _HojaVidaId int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET dist = (select rdc.ReporteId from sistemas_reportedcronograma as rdc where rdc.DetalleCronogramaId = _DetalleCronograma and rdc.HojaVidaId = _HojaVidaId);
  RETURN dist;
END$$
DELIMITER ;
