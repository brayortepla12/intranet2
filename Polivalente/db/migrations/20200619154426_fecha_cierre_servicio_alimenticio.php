<?php

use Phinx\Migration\AbstractMigration;

class FechaCierreServicioAlimenticio extends AbstractMigration
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
        $this->execute("ALTER TABLE `polivalente`.`sa_dhd` 
        ADD COLUMN `CanDesayunar` TINYINT NULL DEFAULT 1 AFTER `CId`,
        ADD COLUMN `CanAlmorzar` TINYINT NULL DEFAULT 1 AFTER `CanDesayunar`,
        ADD COLUMN `CanCenar` TINYINT NULL DEFAULT 1 AFTER `CanAlmorzar`;");
        
        $this->execute("ALTER TABLE `polivalente`.`sa_hd`
        ADD COLUMN `FCD` DATETIME NULL COMMENT 'Fecha Cierre Desayuno'  AFTER `ModifiedAt`,
        ADD COLUMN `FCA` DATETIME NULL COMMENT 'Fecha Cierre Almuerzo' AFTER `FCD`,
        ADD COLUMN `FCC` DATETIME NULL COMMENT 'Fecha Cierre Cena' AFTER `FCA`;");
        
        $this->execute("ALTER TABLE `polivalente`.`sa_hd` 
        ADD COLUMN `CD` TINYINT NULL DEFAULT 0 AFTER `ModifiedAt`,
        ADD COLUMN `CA` TINYINT NULL DEFAULT 0 AFTER `CD`,
        ADD COLUMN `CC` TINYINT NULL DEFAULT 0 AFTER `CA`;");
        
        $this->execute("ALTER TABLE `polivalente`.`sa_hd` 
        ADD COLUMN `RD` VARCHAR(50) NULL AFTER `FCC`,
        ADD COLUMN `RA` VARCHAR(50) NULL AFTER `RD`,
        ADD COLUMN `RC` VARCHAR(50) NULL AFTER `RA`;");
        
        $this->execute("ALTER TABLE `polivalente`.`sa_dhd` 
        CHANGE COLUMN `Observacion` `OD` VARCHAR(100) COLLATE 'utf8mb4_spanish_ci' NULL DEFAULT NULL ,
        ADD COLUMN `OA` VARCHAR(100) NULL AFTER `OD`,
        ADD COLUMN `OC` VARCHAR(100) NULL AFTER `OA`;");
        
        $this->execute("ALTER TABLE `polivalente`.`sa_hd` 
        CHANGE COLUMN `Estado` `Estado` ENUM('Inactivo', 'Activo', 'Desayuno', 'Almuerzo', 'Cena', 'Desayuno Preparado', 'Almuerzo Preparado', 'Cena Preparada', 'Finalizado') COLLATE 'utf8mb4_spanish_ci' NULL DEFAULT 'Activo' ;");
        
        $this->execute("INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'sa.listado_hd', 'Hoja De Dietas', '20');
        UPDATE `polivalente`.`permiso` SET `label`='Administrar HD' WHERE `PermisoId`='128';");
        
        $this->execute("UPDATE `polivalente`.`permiso` SET `label`='Solicitud De Dietas' WHERE `PermisoId`='128';
        UPDATE `polivalente`.`permiso` SET `label`='Administrar Dietas' WHERE `PermisoId`='129';");
        
        $this->execute("ALTER TABLE `polivalente`.`sa_hd` 
        CHANGE COLUMN `CD` `CDesayuno` TINYINT(4) NULL DEFAULT '0' ,
        CHANGE COLUMN `CA` `CAlmuerzo` TINYINT(4) NULL DEFAULT '0' ,
        CHANGE COLUMN `CC` `CCena` TINYINT(4) NULL DEFAULT '0',
        ADD COLUMN `CTODOS` VARCHAR(1) NULL AFTER `CCena`;");

        $this->execute("ALTER TABLE `polivalente`.`sa_dhd` 
        CHANGE COLUMN `DId` `DId` INT(11) NULL DEFAULT 0 ,
        CHANGE COLUMN `AId` `AId` INT(11) NULL DEFAULT 0 ,
        CHANGE COLUMN `CId` `CId` INT(11) NULL DEFAULT 0 ;");
    }
}
