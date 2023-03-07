start transaction;
update hojavida  set SedeId= 2, ServicioId= 175 where ServicioId = 66;
update reporte  set SedeId= 2, ServicioId= 175 where ServicioId = 66;
update ronda  set SedeId= 2, ServicioId= 175 where ServicioId = 66;
update almacen_pedidoalmacen set SedeId= 2, ServicioId= 175 where ServicioId = 66;
update pedidoalmacen set SedeId= 2, ServicioId= 175 where ServicioId = 66;
update almacen_relacioncosto set SedeId= 2, ServicioId= 175 where ServicioId = 66;
delete from serviciousuario where ServicioId = 66;
commit;