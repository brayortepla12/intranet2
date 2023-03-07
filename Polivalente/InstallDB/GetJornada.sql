
set @_now = '08:00';
(SELECT 
if((time(@_now) >= time(j.J1E) and time(@_now) <= time(j.J1S)) or ( time(@_now) < time(j.J1E)), 'Jornada 1', 
if((time(@_now) >= time(j.J2E) and time(@_now) <= time(j.J2S)) or ( time(@_now) > time(j.J1S) and time(@_now) < time(j.J2E)), 'Jornada 2', 
'No Tiene')) as Jornada  FROM ct_persona as j where j.PersonaId = 2);



set @_now = '08:00';
(SELECT 
if((time(@_now) >= time(j.J1E) and time(@_now) <= time(j.J1S)) or (time(@_now) < time(j.J1E)), 'Jornada 1', 
'No Tiene') as Jornada  FROM ct_persona as j where j.PersonaId = 2);

