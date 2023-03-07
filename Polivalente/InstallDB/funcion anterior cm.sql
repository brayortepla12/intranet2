CREATE DEFINER=`ospino`@`%` FUNCTION `GetLoteRondaVerificacion`(TipoMedicamentoId int, Fecha date, MedicamentoId int, RondaVerificacionId int) RETURNS varchar(4) CHARSET latin1
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET @ContadorMedicamentos=0;
  SET dist = (
	select b.Item from (select LPAD(@ContadorMedicamentos:=@ContadorMedicamentos + 1, 3, '000') AS Item, a.DetalleRondaVerificacionId, a.MedicamentoId, a.RondaVerificacionId, a.Orden from 
	(select dr.DetalleRondaVerificacionId, dr.MedicamentoId, r.RondaVerificacionId, m.Orden
	from 
	cm_detallerondaverificacion as dr 
	inner join cm_rondaverificacion as r on r.RondaVerificacionId = dr.RondaVerificacionId
	inner join cm_medicamento as m on dr.MedicamentoId = m.MedicamentoId
	where month(r.Fecha) = month(Fecha) and m.TipoMedicamentoId = TipoMedicamentoId and dr.Estado = 'Activo' group by dr.RondaVerificacionId, m.MedicamentoId
	) as a) as b where b.MedicamentoId = MedicamentoId and b.RondaVerificacionId = RondaVerificacionId
  );
  RETURN dist;
END