CREATE DEFINER=`ospino`@`%` trigger Auditoria after insert on ct_control
for each row
begin
  /*insert into auditoria_ctrl (`ControlId`,
  `Fecha`,
  `PersonaId`,
  `Dispositivo`,
  `Tipo`,
  `PermisoId`,
  `Estado`,
  `CreatedBy`,
  `CreatedAt`) values (
  new.ControlId, 
  new.Fecha,
  new.PersonaId,
  new.Dispositivo,
  new.Tipo,
  new.PermisoId,
  new.Estado,
  new.CreatedBy,
  new.CreatedAt);*/
end