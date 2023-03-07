SELECT if(time('2019-06-10 11:40:00') >= addtime(time(GetHoraFinByFecha(p.TurnoId, '2019-06-10 11:40:00')), '00:30:00'), 'Usted esta saliendo muy tarde', 
if(time('2019-06-10 11:40:00') < time(GetHoraFinByFecha(p.TurnoId, '2019-06-10 11:40:00')), 'Usted esta saliendo antes de tiempo.', 'Saliendo')
) as EstadoSalida FROM ct_persona as p where p.PersonaId = 910;