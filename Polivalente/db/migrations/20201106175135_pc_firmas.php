<?php

use Phinx\Migration\AbstractMigration;

class PcFirmas extends AbstractMigration
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
        $this->execute("DROP TABLE IF EXISTS pc_firmas;");
        $this->execute("CREATE table if not exists pc_firmas(
            FirmaId int primary key auto_increment,
            `Fecha` datetime,
            `PersonaId` int,
            `Nombres` varchar(50),
            `Cargo` varchar(50),
            `Firma` mediumblob,
            `ProcesoId` int,
            `SeguimientoId` int(11),
            `Orden` int(11),
            `Estado` enum('Activo', 'Inactivo') DEFAULT 'Activo',
            `CreatedBy` varchar(50) DEFAULT NULL,
            `CreatedAt` datetime DEFAULT NULL,
            `ModifiedBy` varchar(50) DEFAULT NULL,
            `ModifiedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
        );");
    }
}
