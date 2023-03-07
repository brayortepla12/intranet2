<?php

use Phinx\Migration\AbstractMigration;

class SolicitudMtoPro extends AbstractMigration
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
        $this->execute("INSERT INTO `polivalente`.`modulo` (`Nombre`, `State`) VALUES ('Solicitud', 'solicitud.mto-pro');");
        
        $this->execute("UPDATE `polivalente`.`permiso` SET `ModuloId`='22' WHERE `PermisoId`='125';
        UPDATE `polivalente`.`permiso` SET `ModuloId`='22' WHERE `PermisoId`='124';
        UPDATE `polivalente`.`permiso` SET `ModuloId`='22' WHERE `PermisoId`='11';");

        $this->execute("UPDATE `polivalente`.`permiso` SET `State`='solicitud.solicitudAdmin-pol' WHERE `PermisoId`='11';
        UPDATE `polivalente`.`permiso` SET `State`='solicitud.solicitudAdmin-bio' WHERE `PermisoId`='124';
        UPDATE `polivalente`.`permiso` SET `State`='solicitud.solicitudAdmin-sis' WHERE `PermisoId`='125';");

        $this->execute("UPDATE `polivalente`.`permiso` SET `label` = 'Admin Solicitud SISTEMAS' WHERE (`PermisoId` = '125');
        UPDATE `polivalente`.`permiso` SET `label` = 'Admin Solicitud BIOMEDICOS' WHERE (`PermisoId` = '124');
        UPDATE `polivalente`.`permiso` SET `label` = 'Admin Solicitud POLIVALENTE' WHERE (`PermisoId` = '11');");

        $this->execute("UPDATE `polivalente`.`permiso` SET `label` = 'Admin Solicitud SISTEMAS' WHERE (`PermisoId` = '125');
        UPDATE `polivalente`.`permiso` SET `State` = 'solicitud.solicitud-sis', `label` = 'Solicitud MTO. SISTEMAS' WHERE (`PermisoId` = '122');
        UPDATE `polivalente`.`permiso` SET `State` = 'solicitud.solicitud-bio', `label` = 'Solicitud MTO. BIOMEDICOS' WHERE (`PermisoId` = '123');
        UPDATE `polivalente`.`permiso` SET `State` = 'solicitud.solicitud-pol', `label` = 'Solicitud MTO. MANTENIMIENTO', `ModuloId` = '22' WHERE (`PermisoId` = '6');
        UPDATE `polivalente`.`permiso` SET `label` = 'Admin Solicitud MANTENIMIENTO', `Color` = '' WHERE (`PermisoId` = '11');");

    }
}
