SET SQL_SAFE_UPDATES = 0;

rollback;

start transaction;
DELETE n1 FROM ct_control n1
INNER JOIN ct_control n2 on  n1.Fecha = n2.Fecha 
WHERE n1.ControlId > n2.ControlId AND YEAR(n1.Fecha) = 2020 AND MONTH(n1.Fecha) = 11 AND n1.PersonaId = n2.PersonaId;
commit;