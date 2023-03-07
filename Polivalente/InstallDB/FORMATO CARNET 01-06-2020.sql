

ALTER TABLE `polivalente`.`ct_persona` 
ADD COLUMN `FormatoCarnet` VARCHAR(10) NULL DEFAULT 'CIELD' AFTER `UsuarioBiomedicoId`;

SET SQL_SAFE_UPDATES = 0;

UPDATE ct_persona AS P
SET FormatoCarnet = 'ASERDIR'
WHERE p.JefeId = 2469;

SET SQL_SAFE_UPDATES = 1;


