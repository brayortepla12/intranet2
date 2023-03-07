start transaction;
UPDATE servicio
SET Nombre = REPLACE(Nombre, 'CLD', 'CIELD')
WHERE Nombre LIKE '%CLD%';

COMMIT;