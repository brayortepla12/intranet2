app.controller('ViewReporteExtCtrl', ["$scope", "$stateParams", 'ReporteService', 'TokenService', '$state', 'EmpresaService',
    function ($scope, $stateParams, ReporteService, TokenService, $state, EmpresaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope;
        vm.Reportes = [];
        vm.EmailUsuario = "";
        vm.Empresa = "";
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        vm.Imprimir = function () {
            printDiv();
        };
        vm.Firmar = function () {
            swal({
                title: "Desea firmar el reporte?",
                text: "Nota: una vez firmado no se podras deshacer los cambios.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Firmar',
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            },
                    function (isConfirm) {
                        if (isConfirm) {
                            var obj = {
                                ReporteId: vm.reporte.ReporteId,
                                RecibeEmail: vm.EmailUsuario
                            };
                            ReporteService.FirmarReporte(obj).then(function (d) {
                                console.log(d.data)
                                if (typeof d.data == "object") {
                                    vm.reporte.RecibeFirma = d.data.RecibeFirma;
                                    swal({
                                        title: 'Enhorabuena!',
                                        text: 'Se han guardado los cambios!',
                                        type: 'success'
                                    });
                                } else {
                                    swal("Error", d.data, "error");
                                }
                            });


                        } else {
                            swal("Cancelado", "No se han hecho cambios", "error");
                        }
                    });

        };
        vm.AutoFirmarTODO = function () {
            swal({
                title: "¿Deseas FIRMAR TODO?",
                text: "Nota: Cuidado de hacer SPAM!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "FIRMAR!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    ReporteService.AutoFirmarByRecibeId(vm.EmailUsuario).then(function (d) {
                        if (typeof d.data !== 'string') {
                            _init();
                            swal("Enhorabuena!", "Se han firmado todos los reportes.", "success");
                        } else {
                            swal("Error", d.data, "error");
                        }
                    });
                }
            });
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function printDiv() {

            $("#myElementId").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 750,
                title: null,
                doctype: '<!doctype html>'
            });

        }
        vm.isInArray = function (txt, lst) {
            for (var i in lst) {
                if (txt == lst[i]) {
                    return true;
                }
            }
            return false;
        };
        vm.getDiffFalla = function (lst) {
            for (var i in lst) {
                if (lst[i] != "MAL USO" && lst[i] != "ACCESORIOS" && lst[i] != "DESGASTE" && lst[i] != "SIN FALLA") {
                    return lst[i];
                }
            }
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetReporteById(Id) {
            ReporteService.GetReporteById(Id).then(function (d) {
                if (typeof d.data != "string") {
                    vm.reporte = d.data[0];
                    vm.reporte.NumeroReporte = "N°         " + vm.reporte.NumeroReporte;
                    vm.reporte.Repuestos = JSON.parse(vm.reporte.Repuestos);
                    vm.reporte.FallaDetectada = JSON.parse(vm.reporte.FallaDetectada);
                    vm.reporte.MedidasAplicadas = JSON.parse(vm.reporte.MedidasAplicadas);
                    vm.reporte.EstadoFinal = JSON.parse(vm.reporte.EstadoFinal);
                } else {
                    swal("Error", d.data, "error");
                }
            }, function (e) {
                swal("Error", e, "error");
            });
        }

        function GetReporteByRecibeId(RecibeId) {
            ReporteService.GetReporteByRecibeId(RecibeId).then(function (d) {
                if (typeof d.data != "string") {
                    for (var i in d.data) {
                        d.data[i].NumeroReporte = "N°         " + d.data[i].NumeroReporte;
                        d.data[i].Repuestos = JSON.parse(d.data[i].Repuestos);
                        d.data[i].FallaDetectada = JSON.parse(d.data[i].FallaDetectada);
                        d.data[i].MedidasAplicadas = JSON.parse(d.data[i].MedidasAplicadas);
                        d.data[i].EstadoFinal = JSON.parse(d.data[i].EstadoFinal);
                    }
                    vm.Reportes = d.data;
                } else {
                    swal("Error", d.data, "error");
                }
            }, function (e) {
                swal("Error", e, "error");
            });
        }



        function isValidToken(Token) {
            var obj = {
                Token: Token
            }
            TokenService.isValidToken(obj).then(function (d) {
                if (typeof d.data == "object") {
                    vm.EmailUsuario = d.data.sub;
//                    GetReporteById($stateParams.reporte_id);
                    GetReporteByRecibeId(d.data.sub);
                } else {
                    $state.go("login");
                }
            });
        }
        function _init() {
            isValidToken($stateParams.token);
            GetEmpresa();
        }

        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                vm.Empresa = e.data;
            });
        }
        //</editor-fold>
//        printDiv();
        _init();
    }]);



