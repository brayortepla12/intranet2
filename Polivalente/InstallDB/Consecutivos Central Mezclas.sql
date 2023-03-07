drop table if exists cm_Consecutivo;

CREATE TABLE cm_Consecutivo(
  ConsecutivoId int(11) primary key AUTO_INCREMENT,
  MedicamentoId int,
  TipoMedicamentoId int,
  RondaVerificacionId int,
  Mes int,
  Anno int,
  Consecutivo int,
  foreign key (MedicamentoId) references cm_medicamento(MedicamentoId),
  foreign key (RondaVerificacionId) references cm_rondaverificacion(RondaVerificacionId),
  foreign key (TipoMedicamentoId) references cm_tipomedicamento(TipoMedicamentoId),
  Estado varchar(100) DEFAULT 'Activo',
  CreatedBy varchar(200) DEFAULT NULL,
  CreatedAt timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  ModifiedBy varchar(200) DEFAULT NULL,
  ModifiedAt datetime DEFAULT NULL
) ENGINE=InnoDB;



USE `polivalente`;
DROP function IF EXISTS `GetLoteRondaVerificacion`;

DELIMITER $$
USE `polivalente`$$
CREATE DEFINER=`ospino`@`%` FUNCTION `GetLoteRondaVerificacion`(TipoMedicamentoId int, Fecha date, MedicamentoId int, RondaVerificacionId int) RETURNS varchar(4) CHARSET latin1
    DETERMINISTIC
BEGIN 
  DECLARE dist int;
  SET @ContadorMedicamentos=0;
  SET dist = (
	select max(Consecutivo) from cm_consecutivo as c 
	where 
	(c.TipoMedicamentoId = TipoMedicamentoId 
	and c.Mes = Month(Fecha) and c.Anno = Year(Fecha) 
	and c.MedicamentoId = MedicamentoId and c.RondaVerificacionId = RondaVerificacionId)
  );
  RETURN dist;
END$$

DELIMITER ;