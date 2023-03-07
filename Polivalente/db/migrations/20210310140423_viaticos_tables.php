<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ViaticosTables extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->execute("DROP TABLE IF EXISTS via_Terceros;
        DROP TABLE IF EXISTS via_Consecutivo;
        DROP TABLE IF EXISTS via_DetalleLegalizacion;
        DROP TABLE IF EXISTS via_Legalizacion;
        DROP TABLE IF EXISTS via_DetalleSolicitud;
        DROP TABLE IF EXISTS via_Presolicitud;
        DROP TABLE IF EXISTS via_Solicitud;
        DROP TABLE IF EXISTS via_FlujoTrabajo;
        DROP TABLE IF EXISTS via_Proceso;
        DROP TABLE IF EXISTS via_Concepto;");
        
        $this->execute("CREATE table if not exists via_Proceso(
        `ProcesoId` int primary key auto_increment,
        `Nombre` varchar(50),
        `ProcesoLegalizacionId` int,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime,
        foreign key(`ProcesoLegalizacionId`) references via_Proceso(`ProcesoId`),
        INDEX(`ProcesoId`, `ProcesoLegalizacionId`));
        
        create table if not exists via_FlujoTrabajo(
        `FlujoTrabajoId` int primary key auto_increment,
        `Orden` int,
        `UsuarioVerificaId` int,
        `PersonaId` int,
        `ProcesoId` int,
        `MensajeEstado` varchar(30),
        `Prefijo` varchar(10),
        `IsUltimo` tinyint default 0,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime,
        foreign key(`PersonaId`) references ct_persona(`PersonaId`),
        foreign key(`UsuarioVerificaId`) references usuario(`UsuarioId`),
        foreign key(`ProcesoId`) references via_Proceso(`ProcesoId`),
        INDEX(`FlujoTrabajoId`, `UsuarioVerificaId`, `PersonaId`, `ProcesoId`));

        create table if not exists via_Consecutivo(
        `ConsecutivoId` int primary key auto_increment,
        `Consecutivo` int,
        `ModifiedAt` datetime on update current_timestamp,
        INDEX(`ConsecutivoId`));
        
        create table if not exists via_Concepto(
        `ConceptoId` int primary key auto_increment,
        `Concepto` varchar(25),
        `Legalizable` tinyint default 0,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime on update current_timestamp,
        INDEX(`ConceptoId`));
        
        create table if not exists via_PreSolicitud(
        `PreSolicitudId` int primary key auto_increment,
        `SedeId` int,
        `Sede` varchar(15),
        `Fecha` date,
        `UsuarioSolicitaId` int,
        `NombreSolicita` varchar(50) default '',
        `CargoSolicita` varchar(30) default '',
        `ResIsExterno` tinyint default 0,
        `ResPersonaId` int default null,
        `ResCedula` varchar(20) default '',
        `ResPrimerNombre` varchar(25) default '',
        `ResSegundoNombre` varchar(25) default '',
        `ResPrimerApellido` varchar(25) default '',
        `ResSegundoApellido` varchar(25) default '',
        `ResCelular` varchar(15),
        `ResCargo` varchar(50),
        `ResCorreo` varchar(50),
        `DepartamentoOrigen` varchar(50),
        `MunicipioOrigen` varchar(50),
        `DepartamentoOrigenId` int,
        `MunicipioOrigenId` int,
        `DepartamentoDestino` varchar(50),
        `MunicipioDestino` varchar(50),
        `DepartamentoDestinoId` int,
        `MunicipioDestinoId` int,
        `DescripcionSolicitud` text,
        `MotivoRechazada` text,
        `FechaRechazada` datetime,
        `Estado` enum('Inactivo', 'Activo', 'Rechazada', 'Formato Autorizacion') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime on update current_timestamp,
        foreign key(`SedeId`) references sede(`SedeId`),
        INDEX(`PreSolicitudId`, `SedeId`));
        
        create table if not exists via_Solicitud(
        `SolicitudId` int primary key auto_increment,
        `Consecutivo` int,
        `SedeId` int,
        `PreSolicitudId` int NULL default NULL,
        `Sede` varchar(15),
        `TipoSolicitud` enum('PASAJE', 'GASTOS DE REPRESENTACIÓN') default 'PASAJE',
        `Fecha` date,
        `OrdenEncurso` int,
        `ProcesoId` int,
        `UsuarioSolicitaId` int,
        `NombreSolicita` varchar(50) default '',
        `CargoSolicita` varchar(30) default '',
        `ResIsExterno` tinyint default 0,
        `ResPersonaId` int default null,
        `ResCedula` varchar(20) default '',
        `ResPrimerNombre` varchar(25) default '',
        `ResSegundoNombre` varchar(25) default '',
        `ResPrimerApellido` varchar(25) default '',
        `ResSegundoApellido` varchar(25) default '',
        `ResCelular` varchar(25),
        `ResCargo` varchar(30),
        `ResCorreo` varchar(50),
        `ResFirma` blob default '',
        `AprobacionCaja` tinyint default 0,
        `CajaPersonaId` int default null,
        `CajaCargo` varchar(30) default '',
        `CajaPrimerNombre` varchar(25) default '',
        `CajaSegundoNombre` varchar(25) default '',
        `CajaPrimerApellido` varchar(25) default '',
        `CajaSegundoApellido` varchar(25) default '',
        `CajaFirma` blob default '',
        `AprobacionGH` tinyint default 0,
        `FechaGH` datetime,
        `GHPersonaId` int default null,
        `GHCargo` varchar(30) default '',
        `GHPrimerNombre` varchar(25) default '',
        `GHSegundoNombre` varchar(25) default '',
        `GHPrimerApellido` varchar(25) default '',
        `GHSegundoApellido` varchar(25) default '',
        `GHFirma` blob default '',
        `FechaTes` datetime,
        `AprobacionTes` tinyint default 0,
        `TesPersonaId` int default null,
        `TesCargo` varchar(30) default '',
        `TesFirma` blob default '',
        `TesPrimerNombre` varchar(25) default '',
        `TesSegundoNombre` varchar(25) default '',
        `TesPrimerApellido` varchar(25) default '',
        `TesSegundoApellido` varchar(25) default '',
        `DepartamentoOrigen` varchar(50),
        `MunicipioOrigen` varchar(50),
        `DepartamentoOrigenId` int,
        `MunicipioOrigenId` int,
        `DepartamentoDestino` varchar(50),
        `MunicipioDestino` varchar(50),
        `DepartamentoDestinoId` int,
        `MunicipioDestinoId` int,
        `DescripcionSolicitud` text default '',
        `Observacion` text default '',
        `Estado` enum('Inactivo', 'Borrador', 'Activo', 'Entregado', 'Legalizado') default 'Borrador',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime on update current_timestamp,
        foreign key(`ProcesoId`) references via_Proceso(`ProcesoId`),
        foreign key(`GHPersonaId`) references ct_persona(`PersonaId`),
        foreign key(`TesPersonaId`) references ct_persona(`PersonaId`),
        foreign key(`ResPersonaId`) references ct_persona(`PersonaId`),
        foreign key(`SedeId`) references sede(`SedeId`),
        INDEX(`SolicitudId`, `SedeId`, `GHPersonaId`, `TesPersonaId`, `ResPersonaId`));
        
        create table if not exists via_DetalleSolicitud(
        `DetalleSolicitudId` int primary key auto_increment,
        `SolicitudId` int,
        `ConceptoId` int,
        `Concepto` varchar(25),
        `Dias` int default 1,
        `Valor` decimal(10,2),
        `Legalizable` tinyint default 0,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime on update current_timestamp,
        foreign key(`SolicitudId`) references via_Solicitud(`SolicitudId`),
        foreign key(`ConceptoId`) references via_Concepto(`ConceptoId`),
        INDEX(`DetalleSolicitudId`, `ConceptoId`, `SolicitudId`));
        
        /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
        create table if not exists via_Legalizacion(
        `LegalizacionId` int primary key auto_increment,
        `TipoLegalizacion` enum('Normal', 'Aguachica'),
        `SolicitudId` int,
        `Fecha` date,
        `OrdenEncurso` int,
        `ProcesoId` int,
        `ResPersonaId` int default null,
        `ResCargo` varchar(30) default '',
        `ResCedula` varchar(20) default '',
        `ResPrimerNombre` varchar(25) default '',
        `ResSegundoNombre` varchar(25) default '',
        `ResPrimerApellido` varchar(25) default '',
        `ResSegundoApellido` varchar(25) default '',
        `ResCorreo` varchar(25),
        `ResCelular` varchar(25),
        `ResFirma` blob default '',
        `FechaCaja` datetime,
        `AprobacionCaja` tinyint default 0,
        `CajaPersonaId` int default null,
        `CajaCargo` varchar(30) default '',
        `CajaPrimerNombre` varchar(25) default '',
        `CajaSegundoNombre` varchar(25) default '',
        `CajaPrimerApellido` varchar(25) default '',
        `CajaSegundoApellido` varchar(25) default '',
        `CajaFirma` blob default '',
        `FechaCI` datetime,
        `AprobacionCI` tinyint default 0,
        `CIPersonaId` int default null,
        `CICargo` varchar(30) default '',
        `CIPrimerNombre` varchar(25) default '',
        `CISegundoNombre` varchar(25) default '',
        `CIPrimerApellido` varchar(25) default '',
        `CISegundoApellido` varchar(25) default '',
        `CIFirma` blob default '',
        `FechaCont` datetime,
        `AprobacionCont` tinyint default 0,
        `ContPersonaId` int default null,
        `ContCargo` varchar(30) default '',
        `ContPrimerNombre` varchar(25) default '',
        `ContSegundoNombre` varchar(25) default '',
        `ContPrimerApellido` varchar(25) default '',
        `ContSegundoApellido` varchar(25) default '',
        `ContFirma` blob default '',
        `FechaGer` datetime,
        `AprobacionGer` tinyint default 0,
        `GerPersonaId` int default null,
        `GerCargo` varchar(30) default '',
        `GerPrimerNombre` varchar(25) default '',
        `GerSegundoNombre` varchar(25) default '',
        `GerPrimerApellido` varchar(25) default '',
        `GerSegundoApellido` varchar(25) default '',
        `GerFirma` blob default '',
        `FechaTes` datetime,
        `AprobacionTes` tinyint default 0,
        `TesPersonaId` int default null,
        `TesCargo` varchar(30) default '',
        `TesPrimerNombre` varchar(25) default '',
        `TesSegundoNombre` varchar(25) default '',
        `TesPrimerApellido` varchar(25) default '',
        `TesSegundoApellido` varchar(25) default '',
        `TesFirma` blob default '',
        `Descripcion` text default '',
        `NC` varchar(20) default '',
        `RC` varchar(20) default '',
        `DL` decimal(10,2) default 0,
        `Estado` enum('Inactivo', 'Activo', 'Revisado', 'Finalizado') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime on update current_timestamp,
        foreign key(`SolicitudId`) references via_Solicitud(`SolicitudId`),
        foreign key(`CIPersonaId`) references ct_persona(`PersonaId`),
        foreign key(`ContPersonaId`) references ct_persona(`PersonaId`),
        foreign key(`ResPersonaId`) references ct_persona(`PersonaId`),
        foreign key(`ProcesoId`) references via_proceso(`ProcesoId`),
        INDEX(`SolicitudId`, `CajaPersonaId`, `CIPersonaId`, `ResPersonaId`, `ContPersonaId`, `ProcesoId`));
        
        create table if not exists via_DetalleLegalizacion(
        `DetalleLegalizacionId` int primary key auto_increment,
        `LegalizacionId` int,
        `Fecha` date,
        `Factura` varchar(20) default '',
        `Anexo` mediumblob,
        `Responsable` varchar(50) default '',
        `Concepto` varchar(150) default '',
        `Valor` decimal(10,2),
        `NombresPaciente` varchar(50) default '',
        `Tripulacion` varchar(50) default '',
        `Origen` varchar(50) default '',
        `Destino` varchar(50) default '',
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime on update current_timestamp,
        foreign key(`LegalizacionId`) references via_Legalizacion(`LegalizacionId`),
        INDEX(`DetalleLegalizacionId`, `LegalizacionId`));
        
        create table if not exists via_Terceros(
        `TerceroId` int primary key auto_increment,
        `IsExterno` tinyint DEFAULT 0,
        `PersonaId` int NULL,
        `PrimerNombre` varchar(25) DEFAULT '',
        `SegundoNombre` varchar(25) DEFAULT '',
        `PrimerApellido` varchar(25) DEFAULT '',
        `SegundoApellido` varchar(25) DEFAULT '',
        `Nombres` varchar(200) DEFAULT '',
        `Cedula` varchar(50) DEFAULT '',
        `Cargo` varchar(50) DEFAULT '',
        `Celular` varchar(50) DEFAULT '',
        `Email` varchar(50) DEFAULT '',
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime on update current_timestamp,
        INDEX(`TerceroId`, `Cedula`));
        
        INSERT INTO `polivalente`.`via_concepto` (`Concepto`, `Legalizable`) VALUES ('DESAYUNO', '0');
        INSERT INTO `polivalente`.`via_concepto` (`Concepto`, `Legalizable`) VALUES ('ALMUERZO', '0');
        INSERT INTO `polivalente`.`via_concepto` (`Concepto`, `Legalizable`) VALUES ('CENA', '0');
        INSERT INTO `polivalente`.`via_concepto` (`Concepto`, `Legalizable`) VALUES ('EXCEDENTE', '1');
        INSERT INTO `polivalente`.`via_concepto` (`Concepto`, `Legalizable`) VALUES ('COMBUSTIBLE', '1');
        INSERT INTO `polivalente`.`via_concepto` (`Concepto`, `Legalizable`) VALUES ('PEAJE', '1');
        
        INSERT INTO `polivalente`.`via_consecutivo` (`Consecutivo`) VALUES ('6903');
        
        INSERT INTO `polivalente`.`via_proceso` (`Nombre`) VALUES ('Solicitud Viatico');
        INSERT INTO `polivalente`.`via_proceso` (`Nombre`) VALUES ('Legalizacion');
        
        INSERT INTO `polivalente`.`via_flujotrabajo` (`Orden`, `UsuarioVerificaId`, `PersonaId`, `ProcesoId`) VALUES ('0', '273', '2805', '1');
        INSERT INTO `polivalente`.`via_flujotrabajo` (`Orden`, `UsuarioVerificaId`, `PersonaId`, `ProcesoId`) VALUES ('1', '104', '87', '1');
        INSERT INTO `polivalente`.`via_flujotrabajo` (`Orden`, `UsuarioVerificaId`, `PersonaId`, `ProcesoId`) VALUES ('0', '2', '1861', '2');
        INSERT INTO `polivalente`.`via_flujotrabajo` (`Orden`, `UsuarioVerificaId`, `PersonaId`, `ProcesoId`) VALUES ('1', '16', '71', '2');
        UPDATE `polivalente`.`via_flujotrabajo` SET `MensajeEstado` = 'Pendiente Autorizacion G.H' WHERE (`FlujoTrabajoId` = '1');
        UPDATE `polivalente`.`via_flujotrabajo` SET `MensajeEstado` = 'Pendiente Autorizacion Tes' WHERE (`FlujoTrabajoId` = '2');
        UPDATE `polivalente`.`via_flujotrabajo` SET `MensajeEstado` = 'Revisión Pendiente C.I' WHERE (`FlujoTrabajoId` = '3');
        UPDATE `polivalente`.`via_flujotrabajo` SET `MensajeEstado` = 'Revición Pendiente Cont.' WHERE (`FlujoTrabajoId` = '4');
        UPDATE `polivalente`.`via_flujotrabajo` SET `IsUltimo` = '1' WHERE (`FlujoTrabajoId` = '2');
        UPDATE `polivalente`.`via_flujotrabajo` SET `IsUltimo` = '1' WHERE (`FlujoTrabajoId` = '4');
        UPDATE `polivalente`.`via_flujotrabajo` SET `Prefijo` = 'GH' WHERE (`FlujoTrabajoId` = '1');
        UPDATE `polivalente`.`via_flujotrabajo` SET `Prefijo` = 'Tes' WHERE (`FlujoTrabajoId` = '2');
        UPDATE `polivalente`.`via_flujotrabajo` SET `Prefijo` = 'CI' WHERE (`FlujoTrabajoId` = '3');
        UPDATE `polivalente`.`via_flujotrabajo` SET `Prefijo` = 'Cont' WHERE (`FlujoTrabajoId` = '4');
        ALTER TABLE `polivalente`.`via_proceso` 
        ADD COLUMN `HasFirma` TINYINT NULL DEFAULT 0 AFTER `Nombre`;
        UPDATE `polivalente`.`via_flujotrabajo` SET `MensajeEstado` = 'Pendiente Autorizacion Tes' WHERE (`FlujoTrabajoId` = '1');
        UPDATE `polivalente`.`via_flujotrabajo` SET `MensajeEstado` = 'Autorizado Por Tessoreria' WHERE (`FlujoTrabajoId` = '2');
        
        INSERT INTO `polivalente`.`via_proceso` (`Nombre`, `HasFirma`) VALUES ('Legalizacion Aguachica', '0');
        INSERT INTO `polivalente`.`via_flujotrabajo` (`Orden`, `UsuarioVerificaId`, `PersonaId`, `ProcesoId`, `MensajeEstado`, `Prefijo`, `IsUltimo`) VALUES ('0', '2', '1861', '3', 'Pendiente Autorización CI', 'CI', '0');
        INSERT INTO `polivalente`.`via_flujotrabajo` (`Orden`, `UsuarioVerificaId`, `PersonaId`, `ProcesoId`, `MensajeEstado`, `Prefijo`, `IsUltimo`) VALUES ('1', '14', '2974', '3', 'Pendiente Autorización Gerencia', 'Ger', '0');
        UPDATE `polivalente`.`via_flujotrabajo` SET `MensajeEstado` = 'Pendiente Autorización Tes' WHERE (`FlujoTrabajoId` = '1');
        UPDATE `polivalente`.`via_flujotrabajo` SET `MensajeEstado` = 'Revisión Pendiente Cont' WHERE (`FlujoTrabajoId` = '4');
        INSERT INTO `polivalente`.`via_flujotrabajo` (`Orden`, `UsuarioVerificaId`, `PersonaId`, `ProcesoId`, `MensajeEstado`, `Prefijo`, `IsUltimo`) VALUES ('2', '16', '71', '3', 'Causación Cont', 'Cont', '0');
        INSERT INTO `polivalente`.`via_flujotrabajo` (`Orden`, `UsuarioVerificaId`, `PersonaId`, `ProcesoId`, `MensajeEstado`, `Prefijo`, `IsUltimo`) VALUES ('3', '104', '87', '3', 'Revisión y reembolso', 'Tes', '1');
        UPDATE `polivalente`.`via_flujotrabajo` SET `MensajeEstado` = 'Revisión Pendiente CI' WHERE (`FlujoTrabajoId` = '3');
        UPDATE `polivalente`.`via_proceso` SET `HasFirma` = '1', `ProcesoLegalizacionId` = '2' WHERE (`ProcesoId` = '1');
        
        
        
        INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'solicitud.presolicitudViaticos', 'Iniciar Solicitud Viaticos', '22');
        INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'solicitud.generarsolicitudViaticos', 'Generar Solicitud Viaticos', '22');
        INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'solicitud.solicitud.autorizarViaticos', 'Autorizar Viatico', '22');
        INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'solicitud.LegalizacionViaticos', 'Legalizar Viaticos', '22');
        INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'solicitud.LegalizacionViaticosAguachica', 'Legalizar Viaticos Aguachica', '22');
        INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'solicitud.RevisarLegalizacion', 'Revisar Legalización', '22');
        INSERT INTO `polivalente`.`modulo` (`Nombre`, `State`) VALUES ('Viaticos', 'viatico.LegalizacionViaticos');
        UPDATE `polivalente`.`permiso` SET `State` = 'viatico.presolicitudViaticos' WHERE (`PermisoId` = '140');
        UPDATE `polivalente`.`permiso` SET `State` = 'viatico.generarsolicitudViaticos' WHERE (`PermisoId` = '141');
        UPDATE `polivalente`.`permiso` SET `State` = 'viatico.autorizarViaticos' WHERE (`PermisoId` = '142');
        UPDATE `polivalente`.`permiso` SET `State` = 'viatico.LegalizacionViaticos' WHERE (`PermisoId` = '143');
        UPDATE `polivalente`.`permiso` SET `State` = 'viatico.LegalizacionViaticosAguachica' WHERE (`PermisoId` = '144');
        UPDATE `polivalente`.`permiso` SET `State` = 'viatico.RevisarLegalizacion' WHERE (`PermisoId` = '145');
        UPDATE `polivalente`.`permiso` SET `ModuloId` = '23' WHERE (`PermisoId` = '140');
        UPDATE `polivalente`.`permiso` SET `ModuloId` = '23' WHERE (`PermisoId` = '141');
        UPDATE `polivalente`.`permiso` SET `ModuloId` = '23' WHERE (`PermisoId` = '142');
        UPDATE `polivalente`.`permiso` SET `ModuloId` = '23' WHERE (`PermisoId` = '143');
        UPDATE `polivalente`.`permiso` SET `ModuloId` = '23' WHERE (`PermisoId` = '144');
        UPDATE `polivalente`.`permiso` SET `ModuloId` = '23' WHERE (`PermisoId` = '145');
        ");
    }
}
