app.controller('SolNewEventoCtrl', ["$scope", "$rootScope", "SolicitudService", "SesionService", "SOLFactory", "$cacheFactory",
    function ($scope, $rootScope, SolicitudService, SesionService, SOLFactory, $cacheFactory) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope
        const cacheObject = $cacheFactory.get('Solicitud-mto') || $cacheFactory("Solicitud-mto")
        vm.EventoSol = {
            NombreBreveEvento: null,
            IsVisto: 1,
            TipoEvento: "",
            Descripcion: null,
            PedidoFarmaciaId: null,
            Pedido2_0Id: null,
            PedidoId: null,
            ReporteId: null,
            ReporteExternoId: null,
            ProcesoId: null,
            TecnicoResponsable: "",
            SolicitudId: SOLFactory.data.Sol.SolicitudId,
            UsuarioEventoId: SesionService.get("UserData_Polivalente").UserId,
            NombreUsuario: SesionService.get("UserData_Polivalente").NombreCompleto,
            CreatedBy: SesionService.get("UserData_Polivalente").NombreUsuario
        }
        console.log(cacheObject.get('Reportes' + SOLFactory.data.Sol.SolicitudId))
        if (cacheObject.get('Reportes' + SOLFactory.data.Sol.SolicitudId).data.length === 0) {
            vm.EventoSol.TipoEvento = 'Cancelar solicitud'
        } else {
            vm.EventoSol.TipoEvento = 'Fin de la solicitud'
        }
        vm.PREFIJO = SOLFactory.data.PREFIJO
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        vm.SetTipoEvento = (label) => {
            vm.EventoSol.TipoEvento = label === vm.EventoSol.TipoEvento ? '' : label
            vm.EventoSol.NombreBreveEvento = label === vm.EventoSol.NombreBreveEvento ? '' : label
        }
        vm.CreateEvento = () => {
            if (!vm.DatoEvento.$valid) {
                angular.element("[name='" + vm.DatoEvento.$name + "']").find('.ng-invalid:visible:first').focus()
            } else if (vm.EventoSol.TipoEvento === "") {
                swal("Error", 'Debe seleccionar un tipo de evento.', "error")
            } else {
                swal({
                    title: "¿Deseas CREAR este evento?",
                    text: "Nota: Una vez creado podrás modificarlo siempre que no haya pasado mas de 1 dia!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "CREAR EVENTO!",
                    cancelButtonText: "Cancelar!",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        var obj = {
                            EventoSolicitudPol: JSON.stringify(vm.EventoSol),
                            PREFIJO:vm.PREFIJO
                        }
                        SolicitudService.postSolicitud(obj).then(function (d) {
                            if (typeof d.data !== 'string') {
                                swal("Enhorabuena!", "Se han guardado los datos satisfactoriamente.", "success")
                                $rootScope.$broadcast('Close-Evento')
                                vm.EventoSol = {
                                    NombreBreveEvento: null,
                                    IsVisto: 1,
                                    TipoEvento: "",
                                    Descripcion: null,
                                    PedidoFarmaciaId: null,
                                    Pedido2_0Id: null,
                                    PedidoId: null,
                                    ReporteId: null,
                                    ReporteExternoId: null,
                                    ProcesoId: null,
                                    TecnicoResponsable: "",
                                    SolicitudId: SOLFactory.data.Sol.SolicitudId,
                                    UsuarioEventoId: SesionService.get("UserData_Polivalente").UserId,
                                    NombreUsuario: SesionService.get("UserData_Polivalente").NombreCompleto,
                                    CreatedBy: SesionService.get("UserData_Polivalente").NombreUsuario
                                }
                            } else {
                                swal("Error", d.data, "error")
                            }
                        })
                    }
                })
            }
        }
        //</editor-fold>
    }])