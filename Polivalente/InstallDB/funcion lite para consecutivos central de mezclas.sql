USE `polivalente`;
DROP function IF EXISTS `GetLoteRondaVerificacion_lite`;

DELIMITER $$
USE `polivalente`$$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLoteRondaVerificacion_lite`(TipoMedicamentoId int, Fecha date, MedicamentoId int, RondaVerificacionId int) RETURNS varchar(4) CHARSET latin1
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET @ContadorMedicamentos=0;
  SET dist = (
	select max(Consecutivo) from cm_consecutivo as c 
	where 
	(c.TipoMedicamentoId = TipoMedicamentoId 
	and c.Mes = Month(Fecha) and c.Anno = Year(Fecha)  and c.RondaVerificacionId = RondaVerificacionId)
  );
  RETURN dist;
END$$

DELIMITER ;
