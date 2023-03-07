select p.PrimerNombre, p.PrimerApellido, ifnull(month(now()) = month(p.FechaNacimiento) and dayofmonth(now())  = dayofmonth(p.FechaNacimiento), 0) from ct_Persona as p 

