SET SQL_SAFE_UPDATES = 1;
start transaction;
UPDATE
    ct_persona as p
SET p.Estado = if((select count(c.ControlId) from ct_control as c where c.PersonaId = p.PersonaId) > 0, 'Activo', 'Inactivo');

commit;