SELECT s.Nombre as Servicio, dc.DetalleCronogramaId, dc.Mes, u.Email, u.NombreCompleto, c.Vigencia FROM sistemas_detallecronograma as dc
INNER JOIN sistemas_cronograma as c on dc.CronogramaId = c.CronogramaId 
INNER JOIN servicio as s on dc.ServicioId = s.ServicioId
INNER JOIN serviciousuario as su on su.ServicioId = s.ServicioId
INNER JOIN usuario as u on su.UsuarioId = u.UsuarioId
INNER JOIN ct_persona as p on u.Email = p.Usuario collate latin1_spanish_ci
WHERE dc.Mes <= MONTH(NOW()) AND (
	SELECT COUNT(*) FROM sistemas_reportedcronograma as rdc WHERE rdc.DetalleCronogramaId = dc.DetalleCronogramaId
) = 0