start transaction;
update hojavida  set SedeId= 1, ServicioId= 27 where HojaVidaId = 144;
update reporte  set SedeId= 1, ServicioId= 27 where EquipoId = 144;
commit;