<?php

use Phinx\Migration\AbstractMigration;

class TelInventario extends AbstractMigration
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
        $this->execute("INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) 
        VALUES ('ver vista', 'tel.inventario', 'Inventarios', '21');");

        $this->execute("ALTER TABLE `polivalente`.`tel_inventario` 
        ADD COLUMN `EstadoArticulo` ENUM('Nuevo', 'Usado') NULL AFTER `Stock`;");
    }
}
