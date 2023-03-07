ALTER TABLE `polivalente`.`sistemas_hojavida` 
ADD COLUMN `LicenciaAntivirus` TINYINT NULL DEFAULT 0 AFTER `Nombre`,
ADD COLUMN `LicenciaWindows` TINYINT NULL DEFAULT 0 AFTER `LicenciaAntivirus`,
ADD COLUMN `LicenciaOffice` TINYINT NULL DEFAULT 0 AFTER `LicenciaWindows`;
