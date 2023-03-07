SELECT 
    ft.FlujoTrabajoId,
    v.UsuarioId,
    CONCAT(per.PrimerNombre,
            ' ',
            per.PrimerApellido) as Verificador,
    ft.Orden
FROM
    pc_flujotrabajo AS ft
        INNER JOIN
    pc_proceso AS p ON p.ProtocoloId = ft.ProtocoloId
        INNER JOIN
    pc_verificador AS v ON v.FlujoTrabajoId = ft.FlujoTrabajoId
        INNER JOIN
    ct_persona AS per ON v.UsuarioId = per.UsuarioIntranetId
WHERE
    p.ProcesoId = 500
ORDER BY ft.Orden