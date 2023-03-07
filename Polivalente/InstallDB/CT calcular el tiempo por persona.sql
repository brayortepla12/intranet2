/*SELECT c.Fecha, c.Tipo from ct_control as c 
inner join ct_persona as p on c.PersonaId = p.PersonaId
where p.PersonaId = 55 and c.Fecha >= '2019-05-01 00:00:00' and c.Fecha <= '2019-05-03 00:00:00';*/


select  SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(_data.FechaSalida,_data.FechaEntrada)))) from
(
SELECT current_row._row row_entrada,  current_row.ControlId as 'Entrada', current_row.Fecha FechaEntrada,  previous_row._row row_salida , previous_row.ControlId as 'Salida', previous_row.Fecha as FechaSalida
FROM (
  SELECT @rownum := @rownum + 1 as _row, c.* 
  FROM ct_control as c, (SELECT @rownum:=0) r
  where c.Fecha >= '2019-05-01 00:00:00' and c.Fecha <= '2019-06-01 00:00:00' and c.PersonaId = 630 and c.Tipo = 'Entrada' 
  ORDER BY c.Fecha, c.ControlId
) as current_row
LEFT JOIN (
  SELECT @rownum2 := @rownum2 + 1 as _row, cc.* 
  FROM ct_control as cc , (SELECT @rownum2:=0) r
  where cc.Fecha >= '2019-05-01 00:00:00' and cc.Fecha <= '2019-06-01 00:00:00' and cc.PersonaId = 630  and cc.Tipo = 'Salida'
  ORDER BY cc.Fecha, cc.ControlId
) as previous_row ON
  (current_row.ControlId < previous_row.ControlId) AND (current_row._row > previous_row._row - 1) and previous_row.Fecha > current_row.Fecha and (SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(previous_row.Fecha, current_row.Fecha)))) <= time('08:00:00') 
  
  ) 
  as _data where _data.FechaSalida is not null and _data.FechaEntrada is not null ;



 