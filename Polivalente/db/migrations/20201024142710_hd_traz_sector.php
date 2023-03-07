<?php

use Phinx\Migration\AbstractMigration;

class HdTrazSector extends AbstractMigration
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
        ADD COLUMN `SectorPDesayuno` VARCHAR(45) NULL AFTER `OMC`,
        ADD COLUMN `SectorPMM` VARCHAR(45) NULL AFTER `SectorPDesayuno`,
        ADD COLUMN `SectorPAlmuerzo` VARCHAR(45) NULL AFTER `SectorPMM`,
        ADD COLUMN `SectorPMT` VARCHAR(45) NULL AFTER `SectorPAlmuerzo`,
        ADD COLUMN `SectorPCena` VARCHAR(45) NULL AFTER `SectorPMT`,
        ADD COLUMN `SectorPMN` VARCHAR(45) NULL AFTER `SectorPCena`;");

        $this->execute("ALTER TABLE `polivalente`.`sa_dhd` 
        ADD COLUMN `Trasladado` TINYINT NULL DEFAULT 0 AFTER `SectorPMN`");

        $this->execute("INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) 
        VALUES ('ver vista', 'sa.EstadisticasDetalladas', 'Estadisticas Detalladas', '20');");

        $this->execute("UPDATE `polivalente`.`permiso` SET `label`='HD - Estadisticas' WHERE `PermisoId`='137';
        UPDATE `polivalente`.`permiso` SET `label`='HD - Estadisticas Detalladas' WHERE `PermisoId`='138';");
    }
}
