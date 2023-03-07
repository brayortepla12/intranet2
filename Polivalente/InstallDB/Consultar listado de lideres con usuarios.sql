select * from ct_persona as p 
INNER JOIN usuario as u on p.Usuario collate latin1_spanish_ci = u.Email
where p.TipoPersona = 'Lider'