app.controller('ViewReporteExtSistemasCtrl', ["$scope", "$rootScope", "$stateParams", 'ReporteSistemaService', 'TokenService', '$state', 'EmpresaService', 
    'EncabezadoService',
    function ($scope, $rootScope, $stateParams, ReporteSistemaService, TokenService, $state, EmpresaService, EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope;
        vm.Reportes = [];
        vm.PersonaId = "";
        vm.Empresa = "";
        vm.Encabezado = "";
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
                                RecibeEmail: vm.PersonaId
                            };
                            ReporteSistemaService.FirmarReporte(obj).then(function (d) {
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

                    ReporteSistemaService.AutoFirmarByRecibeId(vm.PersonaId).then(function (d) {
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
        function lpad(str, padString, length) {
            while (str.length < length)
                str = padString + str;
            return str;
        }
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
        vm.isInArray = function (lst, txt) {
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
        function GetReporteByPersonaRecibeId(PersonaRecibeId) {
            ReporteSistemaService.GetReporteByPersonaRecibeId(PersonaRecibeId).then(function (d) {
                if (typeof d.data != "string") {
                    for (var i in d.data) {
                        d.data[i].EquipoId;
                        d.data[i].FallaDetectada = typeof d.data[i].FallaDetectada === 'string' ? JSON.parse(d.data[i].FallaDetectada) : null;
                        d.data[i].EstadoFinal = typeof d.data[i].EstadoFinal === 'string' ? JSON.parse(d.data[i].EstadoFinal) : null;
                        d.data[i].Fotos = typeof d.data[i].Fotos === 'string' ? JSON.parse(d.data[i].Fotos) : null;
                        d.data[i].NumeroReporte = 'N°         ' + lpad(d.data[i].ReporteId.toString(), '0', 4);
                        d.data[i].ModifiedBy = vm.PersonaId;
                        d.data[i].TipoReporte = "Manual";
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
                    vm.PersonaId = d.data.sub;
                    GetReporteByPersonaRecibeId(d.data.sub);
                } else {
                    $state.go("login");
                }
            });
        }
        function _init() {
            isValidToken($stateParams.token);
            GetEmpresa();
            GetEncabezado();
        }

        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                vm.Empresa = e.data;
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                vm.Encabezado = e.data;
            });
        }
        //</editor-fold>
//        printDiv();
        _init();
    }]);



