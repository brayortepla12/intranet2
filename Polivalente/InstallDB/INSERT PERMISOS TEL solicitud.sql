select * from usuariopermiso as up where up.PermisoId = 134;

start transaction;
INSERT INTO usuariopermiso(UsuarioId, PermisoId)
SELECT distinct u.UsuarioId, 134 FROM tel_telefonos as t 
inner join ct_persona as p on t.LiderTelefonoId = p.PersonaId
inner join usuario as u on p.UsuarioIntranetId = u.UsuarioId;

rollback;
commit;