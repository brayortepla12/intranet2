<?php

use Phinx\Migration\AbstractMigration;

class PerSerAlim extends AbstractMigration
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
          $this->execute("INSERT INTO `modulo` (`ModuloId`, `Nombre`, `State`, `img`, `codigo_icono`) VALUES
            (20, 'Servicio Alimenticio', 'sa.crear_hd', NULL, NULL);");  
          $this->execute("INSERT INTO `permiso` (`PermisoId`, `Tipo`, `Descripcion`, `Tabla`, `State`, `label`, `Color`, `ModuloId`, `CreatedBy`, `CreatedAt`, `ModifiedBy`, `ModifiedAt`) VALUES
            (128, 'ver vista', NULL, NULL, 'sa.crear_hd', 'Hoja De Dietas ', NULL, 20, NULL, NULL, NULL, NULL);");  
    }
}
