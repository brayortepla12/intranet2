<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class FuncViaticos extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->execute("USE `polivalente`;
        DROP function IF EXISTS `Via_GetTotalPendienteLegalizar`;
        
        DELIMITER $$
        USE `polivalente`$$
        CREATE FUNCTION `Via_GetTotalPendienteLegalizar` (_SolicitudId INT)
        RETURNS INT
        BEGIN
            DECLARE valor INT;
            SET valor = (SELECT SUM(ds.Valor) FROM via_detallesolicitud AS ds WHERE ds.Legalizable = 1 AND ds.SolicitudId = _SolicitudId);
        RETURN valor;
        END$$
        
        DELIMITER ;
        
        
        USE `polivalente`;
        DROP function IF EXISTS `Via_GetTotalLegalizado`;
        
        DELIMITER $$
        USE `polivalente`$$
        CREATE FUNCTION `Via_GetTotalLegalizado` (_LegalizacionId INT)
        RETURNS INT
        BEGIN
            DECLARE valor INT;
            SET valor = (SELECT SUM(dl.Valor) FROM via_detallelegalizacion AS dl 
                INNER JOIN via_legalizacion as l ON dl.LegalizacionId = l.LegalizacionId
                WHERE l.LegalizacionId = _LegalizacionId);
        RETURN valor;
        END$$
        
        DELIMITER ;
        
        USE `polivalente`;
        DROP function IF EXISTS `Via_GetVerificadorLegalizacion`;
        
        DELIMITER $$
        USE `polivalente`$$
        CREATE FUNCTION `Via_GetVerificadorLegalizacion` (_LegalizacionId int)
        RETURNS varchar(50)
        BEGIN
            DECLARE Verificador VARCHAR(50);
            SET Verificador = (SELECT CONCAT(p.PrimerNombre, ' ', p.PrimerApellido) FROM via_legalizacion AS l 
                INNER JOIN via_flujotrabajo AS ft ON l.ProcesoId = ft.ProcesoId
                INNER JOIN ct_persona AS p on ft.PersonaId = p.PersonaId
                WHERE ft.Orden = l.OrdenEncurso AND l.LegalizacionId = _LegalizacionId);
        RETURN Verificador;
        END$$
        
        DELIMITER ;
        
        UPDATE `polivalente`.`permiso` SET `State` = 'viatico.presolicitudViaticos' WHERE (`PermisoId` = '141');
        UPDATE `polivalente`.`permiso` SET `State` = 'tel.Telefonos', `ModuloId` = '21' WHERE (`PermisoId` = '140');
        UPDATE `polivalente`.`permiso` SET `ModuloId` = '23' WHERE (`PermisoId` = '146');
        UPDATE `polivalente`.`permiso` SET `State` = 'viatico.LegalizacionViaticosAguachica' WHERE (`PermisoId` = '145');
UPDATE `polivalente`.`permiso` SET `State` = 'viatico.LegalizacionViaticos' WHERE (`PermisoId` = '144');
UPDATE `polivalente`.`permiso` SET `State` = 'viatico.autorizarViaticos' WHERE (`PermisoId` = '143');
UPDATE `polivalente`.`permiso` SET `State` = 'viatico.generarsolicitudViaticos' WHERE (`PermisoId` = '142');
INSERT INTO `polivalente`.`via_flujotrabajo` (`Orden`, `UsuarioVerificaId`, `PersonaId`, `ProcesoId`, `MensajeEstado`, `Prefijo`, `IsUltimo`) VALUES ('3', '215', '2805', '1', 'Pendiente Autorización Tes', 'GH', '0');
UPDATE `polivalente`.`via_flujotrabajo` SET `Orden` = '2', `MensajeEstado` = 'Autorizado Por Tesoreria' WHERE (`FlujoTrabajoId` = '2');
UPDATE `polivalente`.`via_flujotrabajo` SET `MensajeEstado` = 'Pendiente Aprobación GH' WHERE (`FlujoTrabajoId` = '1');


        ");
    }
}
