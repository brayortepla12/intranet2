drop table if exists RolPermiso;

CREATE TABLE RolPermiso (
  `RolPermisoId` int(11) NOT NULL AUTO_INCREMENT,
  `RolId` int,
  `PermisoId` int,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`RolPermisoId`)
);