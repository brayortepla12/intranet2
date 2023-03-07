ALTER TABLE `polivalente`.`ct_persona` 
ADD COLUMN `HasDobleJornada` TINYINT NULL AFTER `ModifiedAt`,
ADD COLUMN `J1E` VARCHAR(45) NULL AFTER `HasDobleJornada`,
ADD COLUMN `J1S` VARCHAR(45) NULL AFTER `J1E`,
ADD COLUMN `J2E` VARCHAR(45) NULL AFTER `J1S`,
ADD COLUMN `J2S` VARCHAR(45) NULL AFTER `J2E`;

USE `polivalente`;
DROP function IF EXISTS `VerificarJornadaByPersonaWithDJ`;

DELIMITER $$
USE `polivalente`$$
CREATE DEFINER=`ospino`@`%` FUNCTION `VerificarJornadaByPersonaWithDJ`(PersonaId int, Fecha datetime) RETURNS varchar(20)
    DETERMINISTIC
BEGIN 
  DECLARE dist varchar(20);
  SET dist = ((SELECT if( DAYNAME(Fecha) = 'Saturday' or DAYNAME(Fecha) = 'Sunday' , 
	'Jornada 1'
  , if((time(Fecha) >= time(j.J1E) and time(Fecha) <= time(j.J1S)) or ( time(Fecha) < time(j.J1E)), 'Jornada 1', 
		if((time(Fecha) >= time(j.J2E) and time(Fecha) <= time(j.J2S)) or ( time(Fecha) > time(j.J1S) and time(Fecha) < time(j.J2E)), 'Jornada 2', 
		'No Tiene')))
		 as Jornada  FROM ct_persona as j where j.PersonaId = PersonaId));
  RETURN dist;
END$$

DELIMITER ;


USE `polivalente`;
DROP function IF EXISTS `VerificarJornadaByPersonaWithoutDJ`;

DELIMITER $$
USE `polivalente`$$
CREATE DEFINER=`ospino`@`%` FUNCTION `VerificarJornadaByPersonaWithoutDJ`(PersonaId int, Fecha datetime) RETURNS varchar(20)
    DETERMINISTIC
BEGIN 
  DECLARE dist varchar(20);
  SET dist = (SELECT 
'Jornada 1' as Jornada  FROM ct_persona as j where j.PersonaId = PersonaId);
  RETURN dist;
END$$

DELIMITER ;



USE `polivalente`;
DROP function IF EXISTS `GetTiempoByMes`;

DELIMITER $$
USE `polivalente`$$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetTiempoByMes`(PersonaId int, Desde datetime, Hasta datetime) RETURNS time
    DETERMINISTIC
BEGIN 
  DECLARE dist time;
  SET dist = (

select  SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(_data.FechaSalida,_data.FechaEntrada)))) from
(
SELECT current_row._row row_entrada, current_row.ControlId as 'Entrada', current_row.Fecha FechaEntrada,  previous_row._row row_salida , previous_row.ControlId as 'Salida', previous_row.Fecha as FechaSalida
FROM (
  SELECT @rownum := @rownum + 1 as _row, c.* 
  FROM ct_control as c, (SELECT @rownum:=0) r
  where c.Fecha >= Desde and c.Fecha <= Hasta and c.PersonaId = PersonaId and c.Tipo = 'Entrada' 
  ORDER BY c.Fecha, c.ControlId
) as current_row
LEFT JOIN (
  SELECT @rownum2 := @rownum2 + 1 as _row, cc.* 
  FROM ct_control as cc , (SELECT @rownum2:=0) r
  where cc.Fecha >= Desde and cc.Fecha <= Hasta and cc.PersonaId = PersonaId  and cc.Tipo = 'Salida'
  ORDER BY cc.Fecha, cc.ControlId
) as previous_row ON
  (current_row.ControlId < previous_row.ControlId) AND (current_row._row > previous_row._row - 1)and previous_row.Fecha > current_row.Fecha and (SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(previous_row.Fecha, current_row.Fecha)))) <= time('08:00:00')  #day(previous_row.Fecha) = day(current_row.Fecha)
  
  ) 
  as _data where _data.FechaSalida is not null and _data.FechaEntrada is not null
);
  RETURN dist;
END$$

DELIMITER ;







