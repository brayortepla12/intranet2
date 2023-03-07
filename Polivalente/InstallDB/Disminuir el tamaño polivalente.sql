ALTER TABLE `Polivalente`.`reporte` 
ADD COLUMN `ResponsableId` INT NULL AFTER `SolicitudId`,
ADD COLUMN `RecibeId` INT NULL AFTER `ResponsableId`;


start transaction;
update reporte as r
set r.ResponsableId = (select UsuarioId from usuario where NombreCompleto = r.ResponsableNombre), r.RecibeId = (select UsuarioId from usuario where NombreCompleto =r.RecibeNombre);
UPDATE `polivalente`.`reporte` SET `ResponsableFirma`='', `RecibeFirma`='' ;
commit;

