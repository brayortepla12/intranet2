<?php

use Phinx\Migration\AbstractMigration;

class Capacitaciones extends AbstractMigration
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
     * $ vendor/bin/phinx init .
    * $ $EDITOR phinx.yml
    * $ mkdir -p db/migrations db/seeds
    * $ vendor/bin/phinx create MyFirstMigration
    * $ vendor/bin/phinx migrate -e development

     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->execute("drop table if exists cap_FlagCV;
        drop table if exists cap_ResumenVideo;
        drop table if exists cap_Respuesta;
        drop table if exists cap_Sesion;
        drop table if exists cap_Opcion;
        drop table if exists cap_Pregunta;
        drop table if exists cap_Video;
        drop table if exists cap_Categoria;
        drop table if exists cap_Agenda;");
        
        $this->execute("create table if not exists cap_FlagCV(
        `FlagCVId` int primary key auto_increment,
        `Fecha` datetime,
        `VideoId` int,
        `PersonaId` int,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime,
        INDEX(`FlagCVId`));");
        
        $this->execute("create table if not exists cap_Agenda(
        `AgendaId` int primary key auto_increment,
        `PrimerNombre` varchar(50),
        `SegundoNombre` varchar(50),
        `PrimerApellido` varchar(50),
        `SegundoApellido` varchar(50),
        `FechaInicio` datetime,
        `FechaFin` datetime,
        `PersonaId` int,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime,
        INDEX(`AgendaId`));");
        
        
        $this->execute("create table if not exists cap_Sesion(
        `SesionId` int primary key auto_increment,
        `PrimerNombre` varchar(50),
        `SegundoNombre` varchar(50),
        `PrimerApellido` varchar(50),
        `SegundoApellido` varchar(50),
        `Cargo` varchar(50),
        `Fecha` datetime,
        `PersonaId` int,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime,
        INDEX(`SesionId`));");
        
        
        $this->execute("create table if not exists cap_Categoria(
        `CategoriaId` int primary key auto_increment,
        `Nombre` varchar(50),
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime,
        INDEX(`CategoriaId`));");
        
        $this->execute("create table if not exists cap_Video(
        `VideoId` int primary key auto_increment,
        `Nombre` varchar(50),
        `UrlVideo` text,
        `Duracion` varchar(10),
        `Formato` varchar(20),
        `CategoriaId` int,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime,
        foreign key(CategoriaId) references cap_Categoria(CategoriaId),
        INDEX(`CategoriaId`, `VideoId`));");
        
        $this->execute("create table if not exists cap_Pregunta(
        `PreguntaId` int primary key auto_increment,
        `Pregunta` text,
        `VideoId` int,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime,
        foreign key(VideoId) references cap_Video(VideoId),
        INDEX(`PreguntaId`, `VideoId`));");
        
        $this->execute("create table if not exists cap_Opcion(
        `OpcionId` int primary key auto_increment,
        `Opcion` text,
        `PreguntaId` int,
        `EsCorrecta` tinyint default 0,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime,
        foreign key(PreguntaId) references cap_Pregunta(PreguntaId),
        INDEX(`OpcionId`, `PreguntaId`));");
        
        $this->execute("create table if not exists cap_Respuesta(
        `RespuestaId` int primary key auto_increment,
        `EsCorrecta` tinyint default 0,
        `PreguntaId` int,
        `OpcionId` int,
        `PersonaId` int,
        `SesionId` int,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime,
        foreign key(PreguntaId) references cap_Pregunta(PreguntaId),
        foreign key(OpcionId) references cap_Opcion(OpcionId),
        foreign key(PersonaId) references ct_persona(PersonaId),
        foreign key(SesionId) references cap_Sesion(SesionId),
        INDEX(`RespuestaId`, `OpcionId`, `SesionId`));");
        
        
        $this->execute("create table if not exists cap_ResumenVideo(
        `ResumenVideoId` int primary key auto_increment,
        `TiempoActual` varchar(10),
        `Duracion` varchar(10),
        `VideoId` int,
        `PersonaId` int,
        `SesionId` int,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(20) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(20) DEFAULT '',
        `ModifiedAt` datetime,
        foreign key(VideoId) references cap_Pregunta(VideoId),
        foreign key(PersonaId) references ct_persona(PersonaId),
        foreign key(SesionId) references cap_Sesion(SesionId),
        INDEX(`ResumenVideoId`, `VideoId`, `PersonaId`, `SesionId`));
        ");

        $this->execute("INSERT INTO `polivalente`.`cap_categoria` (`Nombre`) VALUES ('Cirugia');");
        $this->execute("INSERT INTO `polivalente`.`cap_categoria` (`Nombre`) VALUES ('Enfermeria');");
        $this->execute("INSERT INTO `polivalente`.`cap_categoria` (`Nombre`) VALUES ('Farmacia');");
        $this->execute("INSERT INTO `polivalente`.`cap_categoria` (`Nombre`) VALUES ('Medicos');");
        $this->execute("INSERT INTO `polivalente`.`cap_categoria` (`Nombre`) VALUES ('Terapia');");
        $this->execute("INSERT INTO `polivalente`.`cap_categoria` (`Nombre`) VALUES ('Transfusional');");
        $this->execute("INSERT INTO `polivalente`.`cap_categoria` (`Nombre`) VALUES ('VIDEO GENERAL');");
    }
}
