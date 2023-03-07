<?php

use Phinx\Migration\AbstractMigration;

class ServAlm1 extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->execute("drop table if exists sa_Distribucion; # Distribucion a limentaria. ej: Desayuno, Almuerzo, Cena");
        $this->execute("DROP TABLE if exists sa_DHD; # Detalle de la Hoja de Dieta");
        $this->execute("drop table if exists sa_HD; # Hoja de Dieta");
        $this->execute("drop table if exists sa_EmpresaUsuario;");
        $this->execute("drop table if exists sa_EmpresaSector;");
        $this->execute("drop table if exists sa_Empresa;");
        $this->execute("drop table if exists sa_Sector;");
        $this->execute("drop table if exists sa_Var; # Variable: tipo de alimento");

        $this->execute("create table if not exists sa_Sector(
            `SectorId` int primary key auto_increment,
            `DESCRIPCION` varchar(120),
            `SECTOR` varchar(10),
            `Estado` enum('Inactivo', 'Activo', 'Finalizada') default 'Activo',
            `CreatedBy` varchar(20) DEFAULT '',
            `CreatedAt` timestamp DEFAULT current_timestamp,
            `ModifiedBy` varchar(20) DEFAULT '',
            `ModifiedAt` datetime,
            INDEX(`SectorId`));");
        $this->execute("create table if not exists sa_Empresa(
            `EmpresaId` int primary key auto_increment,
            `NombreEmpresa` varchar(50),
            `Estado` enum('Inactivo', 'Activo') default 'Activo',
            `CreatedBy` varchar(20) DEFAULT '',
            `CreatedAt` timestamp DEFAULT current_timestamp,
            `ModifiedBy` varchar(20) DEFAULT '',
            `ModifiedAt` datetime,
            INDEX(`EmpresaId`));");
        $this->execute("create table if not exists sa_EmpresaSector(
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
            INDEX(`EmpresaSectorId`,`EmpresaId`,`SectorId`));");
        $this->execute("create table if not exists sa_EmpresaUsuario(
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
            INDEX(`EmpresaUsuarioId`,`EmpresaId`,`UsuarioId`));");
        $this->execute("create table if not exists sa_HD(
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
                INDEX(`SECTOR` ASC, `DESCRIPCION` ASC));");
        $this->execute("create table if not exists sa_Var(
            `VariableId` int primary key auto_increment,
            `Descripcion` varchar(50) not null,
            `Abrv` varchar(10) not null,
            `Estado` enum('Inactivo', 'Activo') default 'Activo',
            `CreatedBy` varchar(20) DEFAULT '',
            `CreatedAt` timestamp DEFAULT current_timestamp,
            `ModifiedBy` varchar(20) DEFAULT '',
            `ModifiedAt` datetime,
            INDEX(`Abrv` ASC));");
        $this->execute("create table if not exists sa_Distribucion(
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
            INDEX(`DistribucionId`));");
        $this->execute("create table if not exists sa_DHD(
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
            INDEX(`HDId`, `DHDId`));");

        $this->execute("UPDATE `polivalente`.`sa_distribucion` SET `Nombre`='MM' WHERE `DistribucionId`='2';");
        $this->execute("UPDATE `polivalente`.`sa_distribucion` SET `Nombre`='MT' WHERE `DistribucionId`='4';");
        $this->execute("UPDATE `polivalente`.`sa_distribucion` SET `Nombre`='MN' WHERE `DistribucionId`='6';");
        $this->execute("INSERT INTO `polivalente`.`sa_empresa` (`NombreEmpresa`) VALUES ('ABAPS SAS');");
        $this->execute("INSERT INTO `polivalente`.`sa_empresausuario` (`EmpresaId`, `UsuarioId`) VALUES ('1', '3');");

        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('AGUACHICA SECTOR UCI ADULTOS', 'AGUCIA');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('GUACHICA SECTOR UCI PEDIATRICA', 'AGUCIP');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('AGUACHICA SECTOR UCI NEONATOS', 'AGUCIN');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('CAPILLACLD', 'CAPILLACLD');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('Cemic CIRUGIA', 'CemicCIR');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION GENERAL CEMIC', 'CEMICHAB');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('OBSERVACION MATERNA CEMIC', 'CEMICHOSG');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('OBSERVACION PEDIATRICA CEIMIC', 'CEMICOP');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('CEMIC URGENCIAS', 'CEMICURG');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('CP OBSERVACION PEDIATRICA', 'CPOBSPED');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION PEDIATRIA PISO 3', 'HOS3PPED');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION MEDICINA INTERNA 2P', 'HOSP2PISO');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION PEDIATRICA PISO 2', 'HOSP2PPED');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION CORONARIA', 'HOSPCOR');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION BASICA PISO3', 'HOSPITALIZ');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UCI NEONATAL CEMIC', 'NEONATOSC');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('OBSERVACION MUJERES', 'OBSERVAURG');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('Observacion Pediatrica Piso 2', 'OBSPED2P');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('OBSERVACION HOMBRES', 'OBSURGHOM');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('CIRUGIA', 'PISO1CIRUG');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UCI ADULTO', 'PISO4UCIAD');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('HOSPITALIZACION 4to PISO', 'SGR4');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- OBSERVACION 1 PISO', 'SIOBS1P');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- OBSERVACION 2 PISO', 'SIOBS2PSEC');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- PRIMER PISO SEC', 'SIPISO1SEC');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI-URGENCIAS', 'SIPISO1URG');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI-CIRUGIA', 'SIPISO2CIR');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- SEGUNDO PISO BIPERSONAL', 'SIPISO2HOS');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- SEGUNDO PISO UNIPERSONAL', 'SIPISO2UNI');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- TERCER PISO BLOQUE A', 'SIPISO3A');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- TERCER PISO BLOQUE B', 'SIPISO3B');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- UCI ADULTOS PISO 3', 'SIPISO3UCI');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('SI- UCI ADULTOS', 'SIUCIA');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('TERAPIAS', 'TERAPIAS');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UNIDAD DE CUIDADO CRITICO CARDIOVASCULAR', 'UCCC');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UCI ADULTOS SECTOR 5 PISO', 'UCIADULTO');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UCI PEDIATRICA', 'UCIPEDIATR');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('UNIDAD DE INTERVENCIONISMO CARDIOVASCULAR', 'UNICAR');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('VIP SEGUNDO PISO', 'VIP');");
        $this->execute("INSERT INTO `polivalente`.`sa_sector` (`DESCRIPCION`, `SECTOR`) VALUES ('VIP TERCER PISO', 'VIPPISO3');");

        $this->execute("# Asignamos todos los sectores a la empresa 1
        INSERT INTO sa_empresasector (EmpresaId, SectorId)
        SELECT 1, SectorId
        FROM   sa_sector;");
        $this->execute("INSERT INTO `polivalente`.`permiso` (`PermisoId`, `Tipo`, `State`, `label`, `ModuloId`) VALUES (NULL, 'ver vista', 'sa.solicitud_hd', 'HD - Nutricionista', '20');");
        $this->execute("INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'sa.solicitud_hd_prado', 'HD - Servicios', '20');");
        $this->execute("UPDATE `polivalente`.`permiso` SET `State`='sa.listado_hd_cield' WHERE `PermisoId`='129';");
    }
}
