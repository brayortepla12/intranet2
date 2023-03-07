<?php

use Phinx\Migration\AbstractMigration;

class ServicioAlimentos extends AbstractMigration {

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
    public function change() {
        $this->execute("
            drop table if exists sa_DHD;
            drop table if exists sa_HD;
            drop table if exists sa_Var;");

        $this->execute("create table if not exists sa_HD(
	    `HDId` int primary key auto_increment,
        `DESCRIPCION` varchar(120),
        `SECTOR` varchar(10),
        `Fecha` date,
        `Hora` time,
        `Responsable` varchar(150),
        `UResponsableId` int,
        `ResponsableId` int,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(200) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(200) DEFAULT '',
        `ModifiedAt` datetime,
        foreign key(UResponsableId) references usuario(UsuarioId),
        foreign key(ResponsableId) references ct_persona(PersonaId),
            INDEX(`SECTOR` ASC, `DESCRIPCION` ASC));");
        
        $this->execute("create table if not exists sa_Var(
        `VariableId` int primary key auto_increment,
        `Descripcion` varchar(50) not null,
        `Abrv` varchar(10) not null,
        `Estado` enum('Inactivo', 'Activo') default 'Activo',
        `CreatedBy` varchar(200) DEFAULT '',
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(200) DEFAULT '',
        `ModifiedAt` datetime,
        INDEX(`Abrv` ASC));");
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
        `ESTADOPSALIDA` tinyint,
        `FNACIMIENTO` date,
        `FechaSuspendido` datetime,
        `Desayuno` varchar(10) default '',
        `Almuerzo` varchar(10) default '',
        `Cena` varchar(10) default '',
        `FIHD` date,
        `HIHD` time,
        `HDId` int,
        `DId` int,
        `AId` int,
        `CId` int,
        `Observacion` varchar(200),
        `Estado` enum('Inactivo', 'Activo', 'Suspendido') default 'Activo',
        `CreatedBy` varchar(200) DEFAULT NULL,
        `CreatedAt` timestamp DEFAULT current_timestamp,
        `ModifiedBy` varchar(200) DEFAULT NULL,
        `ModifiedAt` datetime,
        foreign key(`HDId`) references sa_HD(HDId),
        foreign key(`DId`) references sa_var(VariableId),
        foreign key(`AId`) references sa_var(VariableId),
        foreign key(`CId`) references sa_var(VariableId),
        INDEX(`HDId`,`DId`,`AId`,`CId`,`Desayuno`,`Almuerzo`,`Cena`));");
    }
}