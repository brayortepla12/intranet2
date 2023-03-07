ALTER TABLE `polivalente`.`pol_eventosolicitud` 
ADD COLUMN `TecnicoResponsable` VARCHAR(200) NULL AFTER `IsInProceso`;
ALTER TABLE `polivalente`.`biomedicos_eventosolicitud` 
ADD COLUMN `TecnicoResponsable` VARCHAR(200) NULL AFTER `IsInProceso`;
ALTER TABLE `polivalente`.`sistemas_eventosolicitud` 
ADD COLUMN `TecnicoResponsable` VARCHAR(200) NULL AFTER `IsInProceso`;
