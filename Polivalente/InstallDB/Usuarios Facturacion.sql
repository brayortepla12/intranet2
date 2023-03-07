SELECT 
u.UsuarioId, u.NombreCompleto, u.TokenFB, u.FCield, u.FPrado, u.NombreUsuario, u.CreatedBy, u.FechaCreacion,
(Select count(*) from usuariopermiso as up where up.UsuarioId = u.UsuarioId) as CPermisos,
(Select count(*) from serviciousuario as su where su.UsuarioId = u.UsuarioId) as CServicios
FROM polivalente.usuario as u where FCield = 1 or FPrado = 1;

