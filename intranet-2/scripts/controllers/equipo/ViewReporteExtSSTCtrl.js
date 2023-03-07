app.controller('ViewReporteExtSSTCtrl', ["$scope", "$stateParams", 'ReporteSSTService', 'TokenService', '$state','EmpresaService',
    function ($scope, $stateParams, ReporteSSTService, TokenService, $state,EmpresaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Reportes = [];
        $scope.EmailUsuario = "";
        $scope.Empresa = "";
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Imprimir = function () {
            printDiv();
        };
        $scope.Firmar = function () {
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
                                ReporteId: $scope.reporte.ReporteId,
                                RecibeEmail: $scope.EmailUsuario
                            };
                            ReporteSSTService.FirmarReporte(obj).then(function (d) {
                                console.log(d.data)
                                if (typeof d.data == "object") {
                                    $scope.reporte.RecibeFirma = d.data.RecibeFirma;
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
        $scope.AutoFirmarTODO = function () {
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

                    ReporteSSTService.AutoFirmarByEmail($scope.EmailUsuario).then(function (d) {
                        console.log(d.data);
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
        $scope.isInArray = function (lst,txt) {
            for (var i in lst) {
                if (txt == lst[i]) {
                    return true;
                }
            }
            return false;
        };
        $scope.getDiffFalla = function (lst) {
            for (var i in lst) {
                if (lst[i] != "MAL USO" && lst[i] != "ACCESORIOS" && lst[i] != "DESGASTE" && lst[i] != "SIN FALLA") {
                    return lst[i];
                }
            }
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetReporteById(Id) {
            ReporteSSTService.GetReporteById(Id).then(function (d) {
                console.log(d.data)
                if (typeof d.data != "string") {
                    $scope.reporte = d.data[0];
                    $scope.reporte.NumeroReporte = "N°         " + $scope.reporte.NumeroReporte;
                    $scope.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
                    $scope.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
                } else {
                    swal("Error", d.data, "error");
                }
            }, function (e) {
                swal("Error", e, "error");
            })
        }
        
        function GetReporteByEmail(Email) {
            ReporteSSTService.GetReporteByEmail(Email).then(function (d) {
                console.log(d.data)
                if (typeof d.data != "string") {
                    for (var i in d.data) {
                        d.data[i].NumeroReporte = "N°         " + d.data[i].NumeroReporte;
                        d.data[i].FallaDetectada = JSON.parse(d.data[i].FallaDetectada);
                        d.data[i].EstadoFinal = JSON.parse(d.data[i].EstadoFinal);
                    }
                    $scope.Reportes = d.data;
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
            };
            TokenService.isValidToken(obj).then(function (d) {
                console.log(d.data)
                if (typeof d.data == "object") {
                    $scope.EmailUsuario = d.data.sub;
                    GetReporteByEmail(d.data.sub);
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
                $scope.Empresa = e.data;
            });
        }
        //</editor-fold>
//        printDiv();
        _init();
    }]);



