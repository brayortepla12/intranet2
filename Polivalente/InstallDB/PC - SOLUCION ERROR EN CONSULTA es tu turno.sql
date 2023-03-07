USE `polivalente`;


USE `polivalente`;
DROP function IF EXISTS `polivalente`.`PC_VerificarSiTurno`;


DELIMITER $$
USE `polivalente`$$
CREATE DEFINER=`ospino`@`%` FUNCTION `PC_VerificarSiTurno`(_ORDEN int, _USUARIOID int, _PROTOCOLOID int) RETURNS int
BEGIN
	DECLARE dist int;
    SET @Row_number_FT = -1;
    SET dist = (
	SELECT Tabla.UsuarioId = _USUARIOID FROM (
	SELECT T.FlujoTrabajoId, T.UsuarioId, @Row_number_FT := @Row_number_FT + 1 as Orden FROM (
	SELECT _ft.FlujoTrabajoId, _v.UsuarioId FROM ct_persona as _per
	STRAIGHT_JOIN pc_verificador as _v on _per.UsuarioIntranetId = _v.UsuarioId
	STRAIGHT_JOIN pc_flujotrabajo as _ft  on _ft.FlujoTrabajoId = _v.FlujoTrabajoId 
	WHERE _ft.ProtocoloId = _PROTOCOLOID AND _ft.Estado = 'Activo' AND _v.Estado = 'Activo' order by _ft.Orden ) as T ) AS Tabla
	WHERE Tabla.Orden = _ORDEN);
RETURN dist;
END$$

DELIMITER ;


