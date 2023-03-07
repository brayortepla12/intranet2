<?php

use Phinx\Migration\AbstractMigration;

class RondaMantenimientoPol extends AbstractMigration
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
        // execute()
        $this->execute("
            drop table if exists pol_DetalleRondaMant;
            drop table if exists pol_RondaMant;
            create table if not exists pol_RondaMant(
                RondaMantId int primary key auto_increment,
                `SedeId` int,
                `Fecha` date,
                `Hora` time,
                `Responsable` varchar(50),
                `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
                    `CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
                    `CreatedAt` TIMESTAMP,
                    `ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
                    `ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
            );

            create table if not exists pol_DetalleRondaMant(
                DetalleRondaMantId int primary key auto_increment,
                `ServicioId` int,
                `Descripcion` varchar(200),
                `TecnicoResponsable` text,
                `Cumplimiento` char(2),
                `CoordinadorFirmaId` varchar(100),
                `Observaciones` text,
                RondaMantId INT,
                IsFirmado TINYINT DEFAULT '0',
                `Estado` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Activo',
                    `CreatedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
                    `CreatedAt` TIMESTAMP,
                    `ModifiedBy` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
                    `ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
            );
            INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'polivalente.rondaMantAdmin', 'Administrar Ronda Mant. ', '1');");
        
        $this->execute("ALTER TABLE `polivalente`.`ct_persona` 
        ADD INDEX `Cedula` (`Cedula` ASC),
        ADD INDEX `CodigoTarjeta` (`CodigoTarjeta` ASC);");
    }
}
