SET @Contador=0;
SELECT LPAD(@Contador:=@Contador + 1, 3, '000') AS Item, 
        m.Nombre as DispositivoMedico,
        m.Concentracion, m.Laboratorio, m.Lote, m.FechaVencimiento, m.RegistroInvima,
        Sum(IF(m.Concentracion <> 0,(dr.Dosis * m.VolumenFinal) / m.Concentracion, 0)) * Sum(dr.Cantidad) /  m.VolumenFinal as Cantidad
        from cm_detallerondaverificacion as dr
        inner join cm_medicamento as m on m.MedicamentoId = dr.MedicamentoId 
        inner join cm_rondaverificacion as r on r.RondaVerificacionId = dr.RondaVerificacionId  
        where dr.RondaVerificacionId = 2 and dr.EstadoPaciente <> 'Suspender' and dr.MedicamentoId = 1
        group by m.Nombre
        
        UNION
        
SELECT LPAD(@Contador:=@Contador + 1, 3, '000') AS Item, 
		IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 ,
        (select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 5) , 
        IF(dr.Volumen  <> 0,(select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId), 'N/A' )) as DispositivoMedico,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 ,
        (select dm.Concentracion from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 5) , 
        (select dm.Concentracion from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as Concentracion,
		IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 ,
        (select dm.Laboratorio from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 5) , 
        (select dm.Laboratorio from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as Laboratorio,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 ,
        (select dm.Lote from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 5) , 
        (select dm.Lote from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as Lote,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 ,
        (select dm.FechaVencimiento from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 5) , 
        (select dm.FechaVencimiento from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as FechaVencimiento,
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 ,
        (select dm.RegistroInvima from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 5) , 
        (select dm.RegistroInvima from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId)) as RegistroInvima,
        count(IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 ,
        (select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 5) , 
        IF(dr.Volumen  <> 0,(select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId), 'N/A' ))) as Cantidad
        from cm_detallerondaverificacion as dr
        inner join cm_medicamento as m on m.MedicamentoId = dr.MedicamentoId 
        inner join cm_rondaverificacion as r on r.RondaVerificacionId = dr.RondaVerificacionId  
        where dr.RondaVerificacionId = 2 and dr.EstadoPaciente <> 'Suspender' and dr.MedicamentoId = 1 and
        IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 ,
        (select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 5) , 
        IF(dr.Volumen  <> 0,(select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId), 'N/A' )) <> 'N/A'
        group by IF((dr.Sector = 'UCIPEDIATR' or dr.Sector = 'NEONATOSC') and dr.Volumen <= 10 ,
        (select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = 5) , 
        IF(dr.Volumen  <> 0,(select dm.Nombre from cm_dispositivomedico as dm where dm.DispositivoMedicoId = dr.VehiculoId), 'N/A' ))
        
        UNION
                
        SELECT LPAD(@Contador:=@Contador + 1, 3, '000') AS Item, dm.Nombre as DispositivoMedico, dm.Concentracion, dm.Laboratorio, dm.Lote,dm.FechaVencimiento, dm.RegistroInvima, dmr.Cantidad
        from cm_DispositivoMedicoByRonda as dmr
        inner join cm_dispositivomedico as dm on dm.DispositivoMedicoId = dmr.DispositivoMedicoId
        where dmr.RondaVerificacionId = 2 and dmr.MedicamentoId = 1;

