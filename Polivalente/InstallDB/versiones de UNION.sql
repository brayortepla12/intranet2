SET @OtraTarifaId = 0;
SELECT t.*, @OtraTarifaId := t.OtroId as _OtroId FROM tm_tarifa as t 
where t.TarifaId = 8
UNION
SELECT t.*, @OtraTarifaId := t.OtroId as _OtroId FROM tm_tarifa as t 
where t.TarifaId = @OtraTarifaId;



select p.PermisoId,p.Tipo,p.State,p.label,p.ModuloId,up.UsuarioPermisoId from permiso as p
inner join usuariopermiso as up on p.PermisoId = up.PermisoId
where up.UsuarioId = 3