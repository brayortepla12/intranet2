<?php

use Phinx\Migration\AbstractMigration;

class BodegaPedidos extends AbstractMigration
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
        $this->execute("ALTER TABLE `polivalente`.`almacen_pedidoalmacen` 
        ADD COLUMN `Bodega` ENUM('Almacen', 'Central') NULL AFTER `PedidoAlmacenId`;");
        
        $this->execute("UPDATE almacen_pedidoalmacen as p
        SET Bodega = (select a.ArticuloPara from almacen_articulo as a 
        inner join almacen_itempedido as ip on ip.ArticuloId = a.ArticuloId
        where ip.PedidoAlmacenId = p.PedidoAlmacenId limit 1)");
        
    }
}
