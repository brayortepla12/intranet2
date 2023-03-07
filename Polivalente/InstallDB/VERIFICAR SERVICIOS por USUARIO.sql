select s.Nombre, u.NombreCompleto, u.IsSistemas, u.IsPolivalente from servicio as s 
inner join serviciousuario as ser on s.ServicioId = ser.ServicioId
inner join usuario as u on u.UsuarioId = ser.UsuarioId
where s.ServicioId = 200