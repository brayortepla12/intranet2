start transaction;
set @var = 6;
INSERT usuariopermiso (UsuarioId, PermisoId) select u.UsuarioId, @var from usuario as u
where (select count(up.UsuarioId) from usuariopermiso as up where u.UsuarioId = up.UsuarioId and up.PermisoId = @var) = 0 and u.Estado = 1;

set @var = 122;
INSERT usuariopermiso (UsuarioId, PermisoId) select u.UsuarioId, @var from usuario as u
where (select count(up.UsuarioId) from usuariopermiso as up where u.UsuarioId = up.UsuarioId and up.PermisoId = @var) = 0 and u.Estado = 1;

set @var = 123;
INSERT usuariopermiso (UsuarioId, PermisoId) select u.UsuarioId, @var from usuario as u
where (select count(up.UsuarioId) from usuariopermiso as up where u.UsuarioId = up.UsuarioId and up.PermisoId = @var) = 0 and u.Estado = 1;


rollback;

commit;


