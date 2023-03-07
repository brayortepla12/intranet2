<?php

use Phinx\Migration\AbstractMigration;

class ReporteFirma extends AbstractMigration
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
        $this->execute("ALTER TABLE `polivalente`.`reporte` 
        ADD COLUMN `ChangeCT` TINYINT NULL DEFAULT 0 COMMENT 'ESTE CAMPO SERVIRA COMO BANDERA... 
        ME INDICARA CUANDO ES UN USUARIO VIEJO DE ESTE DEBO OBTENER LA FIRMA, A PARTIR DEL 05-06-2020, DEBO OBTENER LA FIRMA DE LA TABLA CT_PERSONA' 
        AFTER `EstadoReporte`;");
        $this->execute("ALTER TABLE `polivalente`.`sistemas_reporte` 
        ADD COLUMN `ChangeCT` TINYINT NULL DEFAULT 0 COMMENT 'ESTE CAMPO SERVIRA COMO BANDERA... 
        ME INDICARA CUANDO ES UN USUARIO VIEJO DE ESTE DEBO OBTENER LA FIRMA, A PARTIR DEL 05-06-2020, DEBO OBTENER LA FIRMA DE LA TABLA CT_PERSONA' 
        AFTER `EstadoReporte`;");
    }
}
