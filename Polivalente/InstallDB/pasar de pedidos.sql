start transaction;

delete from usuariopermiso where PermisoId = 34;
delete from usuariopermiso where PermisoId = 56;

INSERT INTO usuariopermiso (UsuarioId, PermisoId)
SELECT UsuarioId, 56
FROM   usuario;

commit;
