drop table if exists pc_oc_Consecutivo;
drop table if exists pc_oc_DetalleOrdenCompra;
drop table if exists pc_oc_OrdenCompra;
drop table if exists pc_oc_FlujoPV;
drop table if exists pc_oc_ProcesoVerificacion;
drop table if exists pc_oc_Vendedor;

create table if not exists pc_oc_Vendedor(
`VendedorId` int primary key auto_increment,
`Nombre` varchar(70),
`Nit` varchar(50),
`Direccion` varchar(70),
`Telefono` varchar(20),
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`VendedorId`));

create table if not exists pc_oc_ProcesoVerificacion(
`ProcesoVerificacionId` int primary key auto_increment,
`Nombre` varchar(50),
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`ProcesoVerificacionId`));

create table if not exists pc_oc_FlujoPV(
`FlujoPVId` int primary key auto_increment,
`UsuarioId` int,
`ProcesoId` int,
`Orden` int,
`IsUltimo` tinyint default 0,
`Prefijo` varchar(15),
`MensajeEstado` varchar(70),
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(`UsuarioId`) references usuario(`UsuarioId`),
foreign key(`ProcesoId`) references pc_oc_procesoverificacion(`ProcesoId`),
INDEX(`UsuarioId`, `ProcesoId`));

create table if not exists pc_oc_OrdenCompra(
`OrdenCompraId` int primary key auto_increment,
`Fecha` datetime,
`Consecutivo` int,
`OrdenEnCurso` int default 0,
`ProcesoVerificacionId` int,
`VendedorId` int,
`ProcesoId` int,
`NumeroCotizacion` varchar(25),
`NombreEmpresa` varchar(70),
`NitEmpresa` varchar(50),
`DireccionEmpresa` varchar(150),
`TelefonoEmpresa` varchar(20),
`EnvNombre` varchar(120),
`EnvEmpresa` varchar(150),
`EnvDireccion` varchar(150),
`EnvCiudad` varchar(70),
`EnvTel` varchar(20),
`SedeId` int,
`ServicioId` int,
`Sede` varchar(20),
`Servicio` varchar(120),
`FormaPago` varchar(70),
`Observacion` text,
`SubTotal` decimal(16,2),
`Total` decimal(16,2),
`Iva` decimal(16,2),
`Envio` decimal(16,2),
`Otro` decimal(16,2),
`ElaboradoId` int,
`FechaElaborado` datetime,
`EmailElaborado` varchar(50),
`NombreElaborado` varchar(50),
`CargoElaborado` varchar(70),
`FirmaElaborado` text,
`RevisadoId` int,
`FechaRevisado` datetime,
`EmailRevisado` varchar(50),
`NombreRevisado` varchar(50),
`CargoRevisado` varchar(70),
`FirmaRevisado` text,
`AprobadoId` int,
`FechaAprobado` datetime,
`EmailAprobado` varchar(50),
`NombreAprobado` varchar(50),
`CargoAprobado` varchar(70),
`FirmaAprobado` text,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(`ElaboradoId`) references ct_personaId(`PersonaId`),
foreign key(`RevisadoId`) references ct_personaId(`PersonaId`),
foreign key(`AprobadoId`) references ct_personaId(`PersonaId`),
foreign key(`VendedorId`) references pc_oc_Vendedor(`VendedorId`),
foreign key(`ProcesoId`) references pc_Proceso(`ProcesoId`),
foreign key(`ProcesoVerificacionId`) references pc_oc_ProcesoVerificacion(`ProcesoVerificacionId`),
INDEX(`OrdenCompraId`, `ElaboradoId`, `RevisadoId`, `AprobadoId`, `ProcesoId`, `ProcesoVerificacionId`));

create table if not exists pc_oc_DetalleOrdenCompra(
`DetalleOrdenCompraId` int primary key auto_increment,
`OrdenCompraId` int,
`Item` varchar(70),
`Descripcion` text,
`Cantidad` int,
`PrecioUnitario` decimal(16,2),
`Total` decimal(16,2),
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(`OrdenCompraId`) references pc_oc_OrdenCompra(`OrdenCompraId`),
INDEX(`DetalleOrdenCompraId`, `OrdenCompraId`));

create table if not exists pc_oc_Consecutivo(
`ConsecutivoId` int primary key auto_increment,
`Numero` int,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`ConsecutivoId`));

INSERT INTO `polivalente`.`pc_oc_procesoverificacion` (`Nombre`) VALUES ('Mantenimiento');
INSERT INTO `polivalente`.`pc_oc_procesoverificacion` (`Nombre`) VALUES ('Biomedico');
INSERT INTO `polivalente`.`pc_oc_procesoverificacion` (`Nombre`) VALUES ('Sistemas');
INSERT INTO `polivalente`.`pc_oc_flujopv` (`UsuarioId`, `Orden`, `Prefijo`, `MensajeEstado`) VALUES ('46', '0', 'Revisado', 'En espera de revisión por sistemas');
INSERT INTO `polivalente`.`pc_oc_flujopv` (`UsuarioId`, `Orden`, `Prefijo`, `MensajeEstado`) VALUES ('14', '1', 'Aprobado', 'En espera aprobación de gerencia');
INSERT INTO `polivalente`.`pc_oc_flujopv` (`UsuarioId`, `Orden`, `Prefijo`, `MensajeEstado`) VALUES ('205', '0', 'Revisado', 'En espera de revisión por mantenimiento');
INSERT INTO `polivalente`.`pc_oc_flujopv` (`UsuarioId`, `Orden`, `Prefijo`, `MensajeEstado`) VALUES ('14', '1', 'Aprobado', 'En espera aprobación de gerencia');
INSERT INTO `polivalente`.`pc_oc_flujopv` (`UsuarioId`, `Orden`, `Prefijo`, `MensajeEstado`) VALUES ('184', '0', 'Revisado', 'En espera de revisión por biomedico');
INSERT INTO `polivalente`.`pc_oc_flujopv` (`UsuarioId`, `Orden`, `Prefijo`, `MensajeEstado`) VALUES ('14', '1', 'Aprobado', 'En espera aprobación de gerencia');
UPDATE `polivalente`.`pc_oc_flujopv` SET `IsUltimo` = '1' WHERE (`FlujoPVId` = '2');
UPDATE `polivalente`.`pc_oc_flujopv` SET `IsUltimo` = '1' WHERE (`FlujoPVId` = '4');
UPDATE `polivalente`.`pc_oc_flujopv` SET `IsUltimo` = '1' WHERE (`FlujoPVId` = '6');
#INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'pc.OrdenCompra', 'Ordenes de compra', '8');
UPDATE `polivalente`.`pc_oc_flujopv` SET `ProcesoId` = '1' WHERE (`FlujoPVId` = '3');
UPDATE `polivalente`.`pc_oc_flujopv` SET `ProcesoId` = '1' WHERE (`FlujoPVId` = '4');
UPDATE `polivalente`.`pc_oc_flujopv` SET `ProcesoId` = '2' WHERE (`FlujoPVId` = '6');
UPDATE `polivalente`.`pc_oc_flujopv` SET `ProcesoId` = '2' WHERE (`FlujoPVId` = '5');
UPDATE `polivalente`.`pc_oc_flujopv` SET `ProcesoId` = '3' WHERE (`FlujoPVId` = '1');
UPDATE `polivalente`.`pc_oc_flujopv` SET `ProcesoId` = '3' WHERE (`FlujoPVId` = '2');
