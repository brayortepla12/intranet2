select DAY(h.DiaMes) as Dia, v.Abreviatura from ct_horario as h 
inner join ct_turno as t on t.TurnoId = h.TurnoId
inner join ct_variable as v on h.VariableId = v.VariableId
where t.ColaboradorId = 2296 and t.JefeId = 1480 
and MONTH(h.DiaMes) = 10 and YEAR(h.DiaMes) = 2019 order by DAY(h.DiaMes)