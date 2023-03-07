select u.NombreUsuario from usuario as u
inner join biomedico.usuario ub on lower(u.NombreUsuario) = lower(ub.NombreUsuario)
where u.Estado = 1 and ub.Estado = 1;


SELECT DISTINCT se.SedeId, se.Nombre FROM serviciousuario as su
                inner join servicio as s on su.ServicioId = s.ServicioId
                inner join sede as se on s.SedeId = se.SedeId
                where su.UsuarioId = 3;

SELECT DISTINCT se.SedeId, se.Nombre FROM biomedico.serviciousuario as su
                inner join biomedico.servicio as s on su.ServicioId = s.ServicioId
                inner join biomedico.sede as se on s.SedeId = se.SedeId
                inner join biomedico.usuario as ub on ub.UsuarioId = su.UsuarioId
                inner join polivalente.usuario up on lower(up.NombreUsuario) = lower(ub.NombreUsuario)
                where su.UsuarioId = 3;