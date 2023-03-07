drop table if exists sa_Distribucion; # Distribucion a limentaria. ej: Desayuno, Almuerzo, Cena
drop table if exists sa_DHD; # Detalle de la Hoja de Dieta
drop table if exists sa_HD; # Hoja de Dieta
drop table if exists sa_EmpresaUsuario;
drop table if exists sa_EmpresaSector;
drop table if exists sa_Empresa;
drop table if exists sa_Sector;
drop table if exists sa_Var; # Variable: tipo de alimento

create table if not exists sa_Sector(
`SectorId` int primary key auto_increment,
`DESCRIPCION` varchar(120),
`SECTOR` varchar(10),
`Estado` enum('Inactivo', 'Activo', 'Finalizada') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`SectorId`));

create table if not exists sa_Empresa(
`EmpresaId` int primary key auto_increment,
`NombreEmpresa` varchar(50),
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`EmpresaId`));

create table if not exists sa_EmpresaSector(
`EmpresaSectorId` int primary key auto_increment,
`EmpresaId` int,
`SectorId` int unique,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(EmpresaId) references sa_Empresa(EmpresaId),
foreign key(SectorId) references sa_Sector(SectorId),
INDEX(`EmpresaSectorId`,`EmpresaId`,`SectorId`));

create table if not exists sa_EmpresaUsuario(
`EmpresaUsuarioId` int primary key auto_increment,
`EmpresaId` int,
`UsuarioId` int,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(EmpresaId) references sa_Empresa(EmpresaId),
foreign key(UsuarioId) references usuario(UsuarioId),
INDEX(`EmpresaUsuarioId`,`EmpresaId`,`UsuarioId`));

create table if not exists sa_HD(
`HDId` int primary key auto_increment,
`DESCRIPCION` varchar(120),
`SECTOR` varchar(10),
`FechaCreacion` datetime,
`Fecha` date,
`EmpDesayunoId` int default null,
`EmpMMId` int default null,
`EmpAlmuerzoId` int default null,
`EmpMTId` int default null,
`EmpCenaId` int default null,
`EmpMNId` int default null,

`UDId` int default 0, # responsable solicitud
`RDId` int default 0,
`UMMId` int default 0,
`RMMId` int default 0,
`UAId` int default 0,
`RAId` int default 0,
`UMTId` int default 0,
`RMTId` int default 0,
`UCId` int default 0,
`RCId` int default 0,
`UMNId` int default 0,
`RMNId` int default 0,
`RD` varchar(50) DEFAULT '',
`RMM` varchar(50) DEFAULT '',
`RA` varchar(50) DEFAULT '',
`RMT` varchar(50) DEFAULT '',
`RC` varchar(50) DEFAULT '',
`RMN` varchar(50) DEFAULT '',


`UPDId` int default 0, # responsable preparacion
`RPDId` int default 0,
`UPMMId` int default 0,
`RPMMId` int default 0,
`UPAId` int default 0,
`RPAId` int default 0,
`UPMTId` int default 0,
`RPMTId` int default 0,
`UPCId` int default 0,
`RPCId` int default 0,
`UPMNId` int default 0,
`RPMNId` int default 0,
`RPD` varchar(50) DEFAULT '',
`RPMM` varchar(50) DEFAULT '',
`RPA` varchar(50) DEFAULT '',
`RPMT` varchar(50) DEFAULT '',
`RPC` varchar(50) DEFAULT '',
`RPMN` varchar(50) DEFAULT '',

`FSDesayuno` datetime, # fecha solicitud
`FSMM` datetime,
`FSAlmuerzo` datetime,
`FSMT` datetime,
`FSCena` datetime,
`FSMN` datetime,
`FCDesayuno` datetime, # fecha cierre
`FCMM` datetime,
`FCAlmuerzo` datetime,
`FCMT` datetime,
`FCCena` datetime,
`FCMN` datetime,
`CDesayuno` tinyint default 0, #cierre
`CMM` tinyint default 0,
`CAlmuerzo` tinyint default 0,
`CMT` tinyint default 0,
`CCena` tinyint default 0,
`CMN` tinyint default 0,
`Desayuno` tinyint default 0,
`MM` tinyint default 0,
`Almuerzo` tinyint default 0,
`MT` tinyint default 0,
`Cena` tinyint default 0,
`MN` tinyint default 0,
`UsuarioId` int,
`Estado` enum('Inactivo', 'Activo', 'Finalizada') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
foreign key(UsuarioId) references usuario(UsuarioId),
foreign key(EmpDesayunoId) references sa_Empresa(EmpresaId),
foreign key(EmpMMId) references sa_Empresa(EmpresaId),
foreign key(EmpAlmuerzoId) references sa_Empresa(EmpresaId),
foreign key(EmpMTId) references sa_Empresa(EmpresaId),
foreign key(EmpCenaId) references sa_Empresa(EmpresaId),
foreign key(EmpMNId) references sa_Empresa(EmpresaId),
	INDEX(`SECTOR` ASC, `DESCRIPCION` ASC));
        
create table if not exists sa_Var(
`VariableId` int primary key auto_increment,
`Descripcion` varchar(50) not null,
`Abrv` varchar(10) not null,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT '',
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT '',
`ModifiedAt` datetime,
INDEX(`Abrv` ASC));
        
create table if not exists sa_Distribucion(
`DistribucionId` int primary key auto_increment,
`Nombre` varchar(20) DEFAULT '',
`Abrv` varchar(5) not null,
`HoraLimite` time,
`HasHoraLimite` tinyint default 0,
`Orden` int unique not null,
`Estado` enum('Inactivo', 'Activo') default 'Activo',
`CreatedBy` varchar(20) DEFAULT NULL,
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT NULL,
`ModifiedAt` datetime,
INDEX(`DistribucionId`));
        
create table if not exists sa_DHD(
`DHDId` int primary key auto_increment,
`HABCAMA` varchar(20) DEFAULT '',
`NOADMISION` varchar(20) DEFAULT '',
`IDAFILIADO` varchar(20) DEFAULT '',
`NOMBREAFI` varchar(150) DEFAULT '',
`TIPOESTANCIA` varchar(150) DEFAULT '',
`IDTERCERO` varchar(15) DEFAULT '',
`RAZONSOCIAL` varchar(150) DEFAULT '',
`SEXO` varchar(10) DEFAULT '',
`FNACIMIENTO` date,
`ESTADOPSALIDA` tinyint,
`Fecha` datetime,
`FIHD` date,
`HIHD` time,
`HDId` int,
`Desayuno` varchar(10),
`DesayunoId` int default null,
`EDesayuno` enum('Inactivo', 'Activo', 'Preparado', 'No tiene') default 'No tiene',
`ODesayuno` varchar(100) DEFAULT '',
`MM` varchar(10),
`MMId` int default null,
`EMM` enum('Inactivo', 'Activo', 'Preparado', 'No tiene') default 'No tiene',
`OMM` varchar(100) DEFAULT '',
`Almuerzo` varchar(10),
`AlmuerzoId` int default null,
`EAlmuerzo` enum('Inactivo', 'Activo', 'Preparado', 'No tiene') default 'No tiene',
`OAlmuerzo` varchar(100) DEFAULT '',
`MT` varchar(10),
`MTId` int default null,
`EMT` enum('Inactivo', 'Activo', 'Preparado', 'No tiene') default 'No tiene',
`OMT` varchar(100) DEFAULT '',
`Cena` varchar(10),
`CenaId` int default null,
`ECena` enum('Inactivo', 'Activo', 'Preparado', 'No tiene') default 'No tiene',
`OCena` varchar(100) DEFAULT '',
`MN` varchar(10),
`MNId` int default null,
`EMN` enum('Inactivo', 'Activo', 'Preparado', 'No tiene') default 'No tiene',
`OMC` varchar(100) DEFAULT '',
`Estado` enum('Inactivo', 'Activo', 'Suspendido') default 'Activo',
`CreatedBy` varchar(20) DEFAULT NULL,
`CreatedAt` timestamp DEFAULT current_timestamp,
`ModifiedBy` varchar(20) DEFAULT NULL,
`ModifiedAt` datetime,
foreign key(`HDId`) references sa_HD(HDId),
foreign key(DesayunoId) references sa_var(VariableId),
foreign key(MMId) references sa_var(VariableId),
foreign key(AlmuerzoId) references sa_var(VariableId),
foreign key(MTId) references sa_var(VariableId),
foreign key(CenaId) references sa_var(VariableId),
foreign key(MNId) references sa_var(VariableId),
INDEX(`HDId`, `DHDId`));

UPDATE `polivalente`.`sa_distribucion` SET `Nombre`='MM' WHERE `DistribucionId`='2';
UPDATE `polivalente`.`sa_distribucion` SET `Nombre`='MT' WHERE `DistribucionId`='4';
UPDATE `polivalente`.`sa_distribucion` SET `Nombre`='MN' WHERE `DistribucionId`='6';

	
INSERT INTO `polivalente`.`sa_empresa` (`NombreEmpresa`) VALUES ('ABAPS SAS');
INSERT INTO `polivalente`.`sa_empresausuario` (`EmpresaId`, `UsuarioId`) VALUES ('1', '3');

INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('AGUACHICA SECTOR UCI ADULTOS', 'AGUCIA');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('GUACHICA SECTOR UCI PEDIATRICA', 'AGUCIP');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('AGUACHICA SECTOR UCI NEONATOS', 'AGUCIN');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('CAPILLACLD', 'CAPILLACLD');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('Cemic CIRUGIA', 'CemicCIR');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION GENERAL CEMIC', 'CEMICHAB');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('OBSERVACION MATERNA CEMIC', 'CEMICHOSG');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('OBSERVACION PEDIATRICA CEIMIC', 'CEMICOP');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('CEMIC URGENCIAS', 'CEMICURG');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('CP OBSERVACION PEDIATRICA', 'CPOBSPED');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION PEDIATRIA PISO 3', 'HOS3PPED');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION MEDICINA INTERNA 2P', 'HOSP2PISO');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION PEDIATRICA PISO 2', 'HOSP2PPED');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION CORONARIA', 'HOSPCOR');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION BASICA PISO3', 'HOSPITALIZ');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UCI NEONATAL CEMIC', 'NEONATOSC');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('OBSERVACION MUJERES', 'OBSERVAURG');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('Observacion Pediatrica Piso 2', 'OBSPED2P');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('OBSERVACION HOMBRES', 'OBSURGHOM');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('CIRUGIA', 'PISO1CIRUG');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UCI ADULTO', 'PISO4UCIAD');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION 4to PISO', 'SGR4');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- OBSERVACION 1 PISO', 'SIOBS1P');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- OBSERVACION 2 PISO', 'SIOBS2PSEC');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- PRIMER PISO SEC', 'SIPISO1SEC');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI-URGENCIAS', 'SIPISO1URG');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI-CIRUGIA', 'SIPISO2CIR');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- SEGUNDO PISO BIPERSONAL', 'SIPISO2HOS');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- SEGUNDO PISO UNIPERSONAL', 'SIPISO2UNI');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- TERCER PISO BLOQUE A', 'SIPISO3A');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- TERCER PISO BLOQUE B', 'SIPISO3B');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- UCI ADULTOS PISO 3', 'SIPISO3UCI');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- UCI ADULTOS', 'SIUCIA');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('TERAPIAS', 'TERAPIAS');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UNIDAD DE CUIDADO CRITICO CARDIOVASCULAR', 'UCCC');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UCI ADULTOS SECTOR 5 PISO', 'UCIADULTO');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UCI PEDIATRICA', 'UCIPEDIATR');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UNIDAD DE INTERVENCIONISMO CARDIOVASCULAR', 'UNICAR');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('VIP SEGUNDO PISO', 'VIP');
INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('VIP TERCER PISO', 'VIPPISO3');

# Asignamos todos los sectores a la empresa 1
INSERT INTO sa_empresasector (EmpresaId, SectorId)
SELECT 1, SectorId
FROM   sa_sector;
	
    #INSERT INTO `polivalente`.`permiso` (`PermisoId`, `Tipo`, `State`, `label`, `ModuloId`) VALUES (NULL, 'ver vista', 'sa.solicitud_hd', 'Solicitud HD', '20');
#UPDATE `polivalente`.`permiso` SET `label`='Administrar Dietas' WHERE `PermisoId`='128';
#UPDATE `polivalente`.`permiso` SET `label`='Administrar Pedidos Dietas' WHERE `PermisoId`='129';

        #UPDATE `polivalente`.`permiso` SET `label`='HD - Servicios' WHERE `PermisoId`='132';
#UPDATE `polivalente`.`permiso` SET `label`='HD - Nutricionista' WHERE `PermisoId`='128';
#UPDATE `polivalente`.`permiso` SET `label`='HD - Empresas' WHERE `PermisoId`='129';
#UPDATE `polivalente`.`permiso` SET `State`='sa.solicitud_hd' WHERE `PermisoId`='132';
#INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'sa.solicitud_hd_prado', 'HD - Servicios', '20');
#UPDATE `polivalente`.`permiso` SET `State`='sa.listado_hd_prado' WHERE `PermisoId`='133';
#UPDATE `polivalente`.`permiso` SET `State`='sa.listado_hd_cield' WHERE `PermisoId`='129';
