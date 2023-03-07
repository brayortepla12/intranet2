<?php

use Phinx\Migration\AbstractMigration;

class TelFirmaEntrega extends AbstractMigration
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
        $this->execute("ALTER TABLE `polivalente`.`tel_entrega` 
        CHANGE COLUMN `Estado` `Estado` ENUM('Inactivo', 'Activo', 'Firmado') CHARACTER SET 'latin1' NULL DEFAULT 'Activo' ,
        ADD COLUMN `FirmaSolicita` TEXT NULL AFTER `CargoSolicita`;");

        $this->execute("ALTER TABLE `polivalente`.`tel_entrega` 
        CHANGE COLUMN `ModifiedAt` `ModifiedAt` DATETIME NULL DEFAULT NULL AFTER `Tipo`,
        ADD COLUMN `FechaFirma` DATETIME NULL AFTER `ModifiedBy`;");

        $this->execute("ALTER TABLE `polivalente`.`tel_entrega` 
        CHANGE COLUMN `FechaFirma` `FechaFirma` DATETIME NULL DEFAULT NULL AFTER `Tipo`,
        CHANGE COLUMN `ModifiedAt` `ModifiedAt` DATETIME NULL DEFAULT NULL AFTER `ModifiedBy`;");

        $this->execute("ALTER TABLE `polivalente`.`tel_entrega` 
        ADD COLUMN `Entrega` VARCHAR(45) NULL AFTER `TelefonoId`,
        ADD COLUMN `CargoEntrega` VARCHAR(45) NULL AFTER `Entrega`,
        ADD COLUMN `FirmaEntrega` TEXT NULL AFTER `CargoEntrega`;");

        $this->execute("ALTER TABLE `polivalente`.`tel_entrega` 
        ADD COLUMN `REntregaId` INT NULL AFTER `TelefonoId`;");


    }
}
