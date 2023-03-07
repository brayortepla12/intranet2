<?php

use Phinx\Migration\AbstractMigration;

class ServAlm2 extends AbstractMigration
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
        $this->execute("UPDATE `polivalente`.`permiso` SET `label`='HD - Empresas' WHERE `PermisoId`='129';");
        $this->execute("UPDATE `polivalente`.`permiso` SET `State`='sa.listado_hd_prado', `label`='HD - Empresas' WHERE `PermisoId`='133';");
        $this->execute("UPDATE `polivalente`.`permiso` SET `State`='sa.crear_hd' WHERE `PermisoId`='132';");
        $this->execute("UPDATE `polivalente`.`permiso` SET `State`='sa.solicitud_hd', `label`='HD - Servicios' WHERE `PermisoId`='128';");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ('NORMAL', 'N', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ('HIPOGRASA', 'HGR', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ('HIPOGLUCIDA', 'HGL', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ('HIPERPROTEICA', 'HP', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ('HIPOCALORICA', 'HIPOC', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ('HIPERCALORICA', 'HIPERC', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ('RENAL', 'RENAL', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ('HIPOSODICA', 'HNA', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ('GASTROPROTECTORA', 'GP', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ( 'LIQUIDA', 'LQ', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ( 'LIQUIDOS CLAROS', 'LQX', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ( 'BLANDA', 'BL', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ( 'SEMIBLANDA', 'SBL', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ( 'COMPLEMENTARIA', 'COMP', 'Activo', '', '2020-08-29 11:50:47', '');");
        $this->execute("INSERT INTO `polivalente`.`sa_var` (`Descripcion`, `Abrv`, `Estado`, `CreatedBy`, `CreatedAt`, `ModifiedBy`) VALUES ( 'ASTRINGENTE', 'ASTRING', 'Activo', '', '2020-08-29 11:50:47', '');");
    }
}
