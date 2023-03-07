

INSERT INTO `polivalente`.`permiso` (`Tipo`, `State`, `label`, `ModuloId`) VALUES ('ver vista', 'pc.auditoria', 'AUDITAR PROCESOS', '8');


DROP FUNCTION IF EXISTS `polivalente`.`PC_GetEventoSolByProcesoId`;
DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `PC_GetEventoSolByProcesoId`(_PROCESOID int) RETURNS int(11)
    DETERMINISTIC
BEGIN 
  DECLARE dist INT;
	SET dist = (
		SELECT t.* FROM (
			SELECT bio.ReporteId FROM biomedicos_eventosolicitud as bio WHERE bio.ProcesoId = _PROCESOID
			UNION ALL
			SELECT sis.ReporteId FROM sistemas_eventosolicitud as sis WHERE sis.ProcesoId = _PROCESOID
			UNION ALL
			SELECT pol.ReporteId FROM pol_eventosolicitud as pol WHERE pol.ProcesoId = _PROCESOID
        ) AS t LIMIT 1
    );
  RETURN dist;
END$$
DELIMITER ;

DROP FUNCTION IF EXISTS `polivalente`.`PC_GetTipoTableByReporteId`;
DELIMITER $$
CREATE DEFINER=`ospino`@`%` FUNCTION `PC_GetTipoTableByReporteId`(_REPORTEID int) RETURNS varchar(15)
    DETERMINISTIC
BEGIN 
  DECLARE dist varchar(15);
	SET dist = (
		SELECT 'biomedicos' as Prefijo FROM biomedicos_eventosolicitud WHERE ReporteId = _REPORTEID
		UNION ALL
		SELECT 'sistemas' as Prefijo  FROM sistemas_eventosolicitud WHERE ReporteId = _REPORTEID
		UNION ALL
		SELECT 'pol' as Prefijo  FROM pol_eventosolicitud WHERE ReporteId = _REPORTEID
    );
  RETURN dist;
END$$
DELIMITER ;

USE `polivalente`;
DROP function IF EXISTS `PC_GetNombreVerificadorActual`;

DELIMITER $$
USE `polivalente`$$
CREATE FUNCTION `PC_GetNombreVerificadorActual`(_ProtocoloId INT, _OrdenEnCurso INT) RETURNS varchar(50)
BEGIN
	DECLARE valor varchar(50);
	SET @RowVerificador = -1;
	SET valor = (SELECT 
    tabla.Nombre
FROM
    (SELECT 
        T.*, @RowVerificador:=@RowVerificador + 1 AS OrdenR
    FROM
        (SELECT 
        CONCAT(_per.PrimerNombre, ' ', _per.PrimerApellido) AS Nombre
    FROM
        ct_persona AS _per
    STRAIGHT_JOIN pc_verificador AS _v ON _per.UsuarioIntranetId = _v.UsuarioId AND _v.Estado = 'Activo'
    STRAIGHT_JOIN pc_flujotrabajo AS _ft ON _ft.FlujoTrabajoId = _v.FlujoTrabajoId AND _ft.Estado = 'Activo'
    WHERE
        _ft.ProtocoloId = _ProtocoloId
            AND _ft.Estado = 'Activo'
    ORDER BY _ft.Orden) AS T) AS tabla
WHERE
    tabla.OrdenR = _OrdenEnCurso
 );
	RETURN valor;
END$$

DELIMITER ;

USE `polivalente`;
DROP procedure IF EXISTS `PC_GetFlujoTrabajoByProcesoId`;

DELIMITER $$
USE `polivalente`$$
CREATE PROCEDURE `PC_GetFlujoTrabajoByProcesoId` (_PROCESOID int)
BEGIN
	SET @RowNFT = -1;
SELECT 
    T.*, @RowNFT:=@RowNFT + 1 AS Orden
FROM
    (SELECT 
        fp.FlujoTrabajoId,
            fp.ProtocoloId,
            fp.Estado,
            protocolo.Nombre AS Protocolo
    FROM
        pc_flujotrabajo AS fp
    STRAIGHT_JOIN pc_proceso AS p ON fp.ProtocoloId = p.ProtocoloId
    STRAIGHT_JOIN pc_protocolo AS protocolo ON fp.ProtocoloId = protocolo.ProtocoloId
    WHERE
        p.ProcesoId = _PROCESOID
            AND fp.Estado <> 'Inactivo'
    ORDER BY fp.Orden) AS T;
END$$

DELIMITER ;



USE `polivalente`;
DROP procedure IF EXISTS `PC_getFirmasByProcesoId`;

DELIMITER $$
USE `polivalente`$$
CREATE PROCEDURE `PC_getFirmasByProcesoId` (_PROCESOID int)
BEGIN
	SELECT f.Fecha, f.Nombres collate latin1_bin as Nombres, f.Cargo collate latin1_bin as Cargo, f.Firma, f.SeguimientoId, f.Orden 
        from pc_firmas as f
        WHERE f.ProcesoId = _PROCESOID AND Estado = 'Activo' GROUP BY f.Nombres, f.Orden
        
        UNION ALL
        
        SELECT * from (
        SELECT 
            '' as Fecha,
            CONCAT(per.PrimerNombre collate latin1_bin,
                    ' ',
                    per.PrimerApellido collate latin1_bin) as Nombres,
            c.Cargo collate latin1_bin as Cargo, 
            NULL as Firma,
            NULL as SeguimientoId,
            ft.Orden
        FROM
            pc_flujotrabajo AS ft
                STRAIGHT_JOIN
            pc_proceso AS p ON p.ProtocoloId = ft.ProtocoloId
                STRAIGHT_JOIN
            pc_verificador AS v ON v.FlujoTrabajoId = ft.FlujoTrabajoId
                STRAIGHT_JOIN
            ct_persona AS per ON v.UsuarioId = per.UsuarioIntranetId
                LEFT JOIN
            ct_cargo AS c ON c.CargoId = per.CargoId
        WHERE
            p.ProcesoId = _PROCESOID and ft.Orden >= p.OrdenEnCurso and ft.Estado = 'Activo' and v.Estado = 'Activo'
         ORDER BY ft.Orden) as t ORDER BY Orden;
END$$

DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `PC_GetUsuario_sistemas_ToNotificarByProcesoId`(_PROCESOID int)
BEGIN
	SET @ROW_FT = -1;
SELECT 
    tabla.*
FROM
    (SELECT 
        t.*, @ROW_FT:=@ROW_FT + 1 AS Orden
    FROM
        (SELECT 
            CONCAT(per.PrimerNombre COLLATE latin1_bin, ' ', per.PrimerApellido COLLATE latin1_bin) AS Nombres,
            per.PersonaId,
            per.Celular,
            u.Email,
            c.Cargo COLLATE latin1_bin AS Cargo
    FROM
        pc_flujotrabajo AS ft
    STRAIGHT_JOIN pc_proceso AS p ON p.ProtocoloId = ft.ProtocoloId
    STRAIGHT_JOIN pc_verificador AS v ON v.FlujoTrabajoId = ft.FlujoTrabajoId
    STRAIGHT_JOIN ct_persona AS per ON v.UsuarioId = per.UsuarioIntranetId
    STRAIGHT_JOIN usuario AS u ON v.UsuarioId = u.UsuarioId
    LEFT JOIN ct_cargo AS c ON c.CargoId = per.CargoId
    WHERE
        p.ProcesoId = _PROCESOID
            AND ft.Orden >= p.OrdenEnCurso
            AND ft.Estado = 'Activo'
            AND v.Estado = 'Activo'
    ORDER BY ft.Orden) AS t
    ORDER BY Orden) AS tabla
    STRAIGHT_JOIN pc_proceso as pro on pro.ProcesoId = _PROCESOID
WHERE
    tabla.Orden <= pro.OrdenEnCurso
UNION ALL
SELECT 
    CONCAT(per.PrimerNombre COLLATE latin1_bin,
            ' ',
            per.PrimerApellido COLLATE latin1_bin) AS Nombres,
    per.PersonaId,
    per.Celular,
    u.Email,
    c.Cargo COLLATE latin1_bin AS Cargo, '-' AS Orden
FROM
    sistemas_eventosolicitud AS e
        INNER JOIN
    sistemas_solicitud AS s ON e.SolicitudId = s.SolicitudId
        INNER JOIN
    usuario AS u ON u.UsuarioId = s.UsuarioSolicitaId
        INNER JOIN
    ct_persona AS per ON u.UsuarioId = per.UsuarioIntranetId
        LEFT JOIN
    ct_cargo AS c ON c.CargoId = per.CargoId
WHERE
    e.ProcesoId = _PROCESOID;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `PC_GetUsuario_biomedicos_ToNotificarByProcesoId`(_PROCESOID int)
BEGIN
	SET @ROW_FT = -1;
SELECT 
    tabla.*
FROM
    (SELECT 
        t.*, @ROW_FT:=@ROW_FT + 1 AS Orden
    FROM
        (SELECT 
            CONCAT(per.PrimerNombre COLLATE latin1_bin, ' ', per.PrimerApellido COLLATE latin1_bin) AS Nombres,
            per.PersonaId,
            per.Celular,
            u.Email,
            c.Cargo COLLATE latin1_bin AS Cargo
    FROM
        pc_flujotrabajo AS ft
    STRAIGHT_JOIN pc_proceso AS p ON p.ProtocoloId = ft.ProtocoloId
    STRAIGHT_JOIN pc_verificador AS v ON v.FlujoTrabajoId = ft.FlujoTrabajoId
    STRAIGHT_JOIN ct_persona AS per ON v.UsuarioId = per.UsuarioIntranetId
    STRAIGHT_JOIN usuario AS u ON v.UsuarioId = u.UsuarioId
    LEFT JOIN ct_cargo AS c ON c.CargoId = per.CargoId
    WHERE
        p.ProcesoId = _PROCESOID
            AND ft.Orden >= p.OrdenEnCurso
            AND ft.Estado = 'Activo'
            AND v.Estado = 'Activo'
    ORDER BY ft.Orden) AS t
    ORDER BY Orden) AS tabla
    STRAIGHT_JOIN pc_proceso as pro on pro.ProcesoId = _PROCESOID
WHERE
    tabla.Orden <= pro.OrdenEnCurso
UNION ALL
SELECT 
    CONCAT(per.PrimerNombre COLLATE latin1_bin,
            ' ',
            per.PrimerApellido COLLATE latin1_bin) AS Nombres,
    per.PersonaId,
    per.Celular,
    u.Email,
    c.Cargo COLLATE latin1_bin AS Cargo, '-' AS Orden
FROM
    biomedicos_eventosolicitud AS e
        INNER JOIN
    biomedicos_solicitud AS s ON e.SolicitudId = s.SolicitudId
        INNER JOIN
    usuario AS u ON u.UsuarioId = s.UsuarioSolicitaId
        INNER JOIN
    ct_persona AS per ON u.UsuarioId = per.UsuarioIntranetId
        LEFT JOIN
    ct_cargo AS c ON c.CargoId = per.CargoId
WHERE
    e.ProcesoId = _PROCESOID;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `PC_GetUsuario_pol_ToNotificarByProcesoId`(_PROCESOID int)
BEGIN
	SET @ROW_FT = -1;
SELECT 
    tabla.*
FROM
    (SELECT 
        t.*, @ROW_FT:=@ROW_FT + 1 AS Orden
    FROM
        (SELECT 
            CONCAT(per.PrimerNombre COLLATE latin1_bin, ' ', per.PrimerApellido COLLATE latin1_bin) AS Nombres,
            per.PersonaId,
            per.Celular,
            u.Email,
            c.Cargo COLLATE latin1_bin AS Cargo
    FROM
        pc_flujotrabajo AS ft
    STRAIGHT_JOIN pc_proceso AS p ON p.ProtocoloId = ft.ProtocoloId
    STRAIGHT_JOIN pc_verificador AS v ON v.FlujoTrabajoId = ft.FlujoTrabajoId
    STRAIGHT_JOIN ct_persona AS per ON v.UsuarioId = per.UsuarioIntranetId
    STRAIGHT_JOIN usuario AS u ON v.UsuarioId = u.UsuarioId
    LEFT JOIN ct_cargo AS c ON c.CargoId = per.CargoId
    WHERE
        p.ProcesoId = _PROCESOID
            AND ft.Orden >= p.OrdenEnCurso
            AND ft.Estado = 'Activo'
            AND v.Estado = 'Activo'
    ORDER BY ft.Orden) AS t
    ORDER BY Orden) AS tabla
    STRAIGHT_JOIN pc_proceso as pro on pro.ProcesoId = _PROCESOID
WHERE
    tabla.Orden <= pro.OrdenEnCurso
UNION ALL
SELECT 
    CONCAT(per.PrimerNombre COLLATE latin1_bin,
            ' ',
            per.PrimerApellido COLLATE latin1_bin) AS Nombres,
    per.PersonaId,
    per.Celular,
    u.Email,
    c.Cargo COLLATE latin1_bin AS Cargo, '-' AS Orden
FROM
    pol_eventosolicitud AS e
        INNER JOIN
    pol_solicitud AS s ON e.SolicitudId = s.SolicitudId
        INNER JOIN
    usuario AS u ON u.UsuarioId = s.UsuarioSolicitaId
        INNER JOIN
    ct_persona AS per ON u.UsuarioId = per.UsuarioIntranetId
        LEFT JOIN
    ct_cargo AS c ON c.CargoId = per.CargoId
WHERE
    e.ProcesoId = _PROCESOID;
END$$
DELIMITER ;



USE `polivalente`;
DROP function IF EXISTS `PC_VerificarSiTurno`;

DELIMITER $$
USE `polivalente`$$
CREATE FUNCTION `PC_VerificarSiTurno` (_ORDEN int, _USUARIOID int, _PROTOCOLOID int)
RETURNS INTEGER
BEGIN
	DECLARE dist int;
    SET @Row_number_FT = -1;
    SET dist = (
	SELECT If(Tabla.UsuarioId, 1, 0) FROM (
	SELECT T.FlujoTrabajoId, T.UsuarioId, @Row_number_FT := @Row_number_FT + 1 as Orden FROM (
	SELECT _ft.FlujoTrabajoId, _v.UsuarioId FROM ct_persona as _per
	STRAIGHT_JOIN pc_verificador as _v on _per.UsuarioIntranetId = _v.UsuarioId
	STRAIGHT_JOIN pc_flujotrabajo as _ft  on _ft.FlujoTrabajoId = _v.FlujoTrabajoId 
	WHERE _ft.ProtocoloId = _PROTOCOLOID AND _ft.Estado = 'Activo' AND _v.Estado = 'Activo' order by _ft.Orden ) as T ) AS Tabla
	WHERE Tabla.Orden = _ORDEN AND Tabla.UsuarioId = _USUARIOID);
RETURN dist;
END$$

DELIMITER ;


USE `polivalente`;
DROP function IF EXISTS `PC_GetProtocoloIdByFlujoTrabajoId`;

DELIMITER $$
USE `polivalente`$$
CREATE FUNCTION `PC_GetProtocoloIdByFlujoTrabajoId` (_FLUJOTRABAJOID INT)
RETURNS INTEGER
BEGIN
	DECLARE dist INT;
    SET dist = (SELECT ft.ProtocoloId FROM pc_flujotrabajo as ft where ft.FlujoTrabajoId = _FLUJOTRABAJOID);
RETURN dist;
END$$

DELIMITER ;




