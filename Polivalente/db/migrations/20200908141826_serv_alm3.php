<?php

use Phinx\Migration\AbstractMigration;

class ServAlm3 extends AbstractMigration
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
        $this->execute("INSERT INTO `polivalente`.`sa_distribucion` (`Nombre`, `Abrv`, `HoraLimite`, `HasHoraLimite`, `Orden`, `Estado`, `CreatedAt`) VALUES ('Desayuno', 'D', '23:59:59', '1', '0', 'Activo', '2020-08-29 11:50:47');");
        $this->execute("INSERT INTO `polivalente`.`sa_distribucion` (`Nombre`, `Abrv`, `HasHoraLimite`, `Orden`, `Estado`, `CreatedAt`) VALUES ('MM', 'MM', '0', '1', 'Activo', '2020-08-29 11:50:47');");
        $this->execute("INSERT INTO `polivalente`.`sa_distribucion` (`Nombre`, `Abrv`, `HoraLimite`, `HasHoraLimite`, `Orden`, `Estado`, `CreatedAt`) VALUES ('Almuerzo', 'A', '10:30:00', '1', '2', 'Activo', '2020-08-29 11:50:47');");
        $this->execute("INSERT INTO `polivalente`.`sa_distribucion` (`Nombre`, `Abrv`, `HasHoraLimite`, `Orden`, `Estado`, `CreatedAt`) VALUES ('MT', 'MT', '0', '3', 'Activo', '2020-08-29 11:50:47');");
        $this->execute("INSERT INTO `polivalente`.`sa_distribucion` (`Nombre`, `Abrv`, `HoraLimite`, `HasHoraLimite`, `Orden`, `Estado`, `CreatedAt`) VALUES ('Cena', 'C', '15:00:00', '1', '4', 'Activo', '2020-08-29 11:50:47');");
        $this->execute("INSERT INTO `polivalente`.`sa_distribucion` (`Nombre`, `Abrv`, `HasHoraLimite`, `Orden`, `Estado`, `CreatedAt`) VALUES ('MN', 'MN', '0', '5', 'Activo', '2020-08-29 11:50:47');");
    }
}
