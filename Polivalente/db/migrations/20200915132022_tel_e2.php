<?php

use Phinx\Migration\AbstractMigration;

class TelE2 extends AbstractMigration
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
        $this->execute("ALTER TABLE `polivalente`.`tel_solicitud` 
        CHANGE COLUMN `Estado` `Estado` ENUM('Inactivo', 'Activo', 'Cancelada', 'Rechazada', 'Entregada') CHARACTER SET 'latin1' NULL DEFAULT 'Activo' ;");
        $this->execute("ALTER TABLE `polivalente`.`tel_entrega` 
        ADD COLUMN `InventarioId` INT NULL AFTER `Descripcion`;");
        $this->execute("ALTER TABLE `polivalente`.`tel_entrega` 
        ADD COLUMN `Solicita` VARCHAR(50) NULL AFTER `TelefonoId`,
        ADD COLUMN `CargoSolicita` VARCHAR(45) NULL AFTER `Solicita`;");
        $this->execute("ALTER TABLE `polivalente`.`tel_entrega` 
        ADD COLUMN `Tipo` VARCHAR(45) NULL AFTER `CargoSolicita`;");
        $this->execute("ALTER TABLE `polivalente`.`tel_entrega` 
        ADD COLUMN `Institucion` VARCHAR(50) NULL AFTER `CargoSolicita`,
        ADD COLUMN `Ciudad` VARCHAR(45) NULL AFTER `Institucion`;");



    }
}
