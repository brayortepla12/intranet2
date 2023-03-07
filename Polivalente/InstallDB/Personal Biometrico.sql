start transaction;
ALTER TABLE `polivalente`.`ct_persona` 
ADD COLUMN `TipoPersona` VARCHAR(45) NULL AFTER `CodigoTarjeta`,
ADD COLUMN `JefeId` INT NULL AFTER `TipoPersona`,
ADD COLUMN `Rh` VARCHAR(45) NULL;

ALTER TABLE `polivalente`.`ct_persona` 
ADD COLUMN `SedeId` INT NULL AFTER `Rh`,
ADD COLUMN `CargoId` INT NULL AFTER `SedeId`;

ALTER TABLE `polivalente`.`ct_persona` 
ADD COLUMN `ServicioId` INT NULL AFTER `SedeId`;

drop table if exists ct_Cargo;
CREATE TABLE  ct_Cargo  (
   CargoId  int(11) NOT NULL AUTO_INCREMENT primary key,
   Cargo  varchar(300),
   Estado  varchar(200) DEFAULT 'Activo',
   CreatedBy  varchar(200) DEFAULT NULL,
   CreatedAt  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   ModifiedBy  varchar(200) DEFAULT NULL,
   ModifiedAt  datetime DEFAULT NULL
);