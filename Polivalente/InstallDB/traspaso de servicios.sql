SET @Servicio_Origen = 67;
SET @Servicio_Destino = 89;
start transaction;
	#Polivalente
	Update HojaVida SET ServicioId = @Servicio_Destino where ServicioId = @Servicio_Origen;
    Update reporte SET ServicioId = @Servicio_Destino where ServicioId = @Servicio_Origen;
    #Sistemas
    Update Sistemas_HojaVida SET ServicioId = @Servicio_Destino where ServicioId = @Servicio_Origen;
    Update Sistemas_reporte SET ServicioId = @Servicio_Destino where ServicioId = @Servicio_Origen;
    #Almacen_Pedido
    Update almacen_pedidoalmacen SET ServicioId = @Servicio_Destino where ServicioId = @Servicio_Origen;
    Update pedidoalmacen SET ServicioId = @Servicio_Destino where ServicioId = @Servicio_Origen;
    Update almacen_relacioncosto SET ServicioId = @Servicio_Destino where ServicioId = @Servicio_Origen;
    #Usuario
    Update serviciousuario SET ServicioId = @Servicio_Destino where ServicioId = @Servicio_Origen;
    #Ronda
    Update Ronda SET ServicioId = @Servicio_Destino where ServicioId = @Servicio_Origen;
    #FormatoServicio
    Update formatoservicio SET ServicioId = @Servicio_Destino where ServicioId = @Servicio_Origen;
    #ELIMINAMOS El SERVICIO
    Delete from Servicio Where ServicioId = @Servicio_Origen;
commit;
rollback;    