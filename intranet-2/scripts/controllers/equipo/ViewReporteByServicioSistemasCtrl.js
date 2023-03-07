app.controller('ViewReporteByServicioSistemasCtrl', ["$scope", "$stateParams", 'ReporteSistemaService', 'SesionService', 'EncabezadoService', 'EmpresaService',
    function ($scope, $stateParams, ReporteSistemaService, SesionService, EncabezadoService, EmpresaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.reporte = {};
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.OpenArchivo = function (url) {
            window.open('http://192.168.8.125:8080/Polivalente/upload_files/' + url, '_blank');
        };
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
                                RecibeEmail: SesionService.get("UserData").Email
                            };
                            ReporteSistemaService.FirmarReporte(obj).then(function (d) {
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
                timeout: 3050,
                title: null,
                doctype: '<!doctype html>'
            });

        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetReporteByServicioId(obj) {
            ReporteSistemaService.GetReporteByServicioId(obj).then(function (d) {
                console.log(d.data)
                if (typeof d.data !== "string") {
                    $scope.reportes = setFormat(d.data);
                } else {
                    swal("Error", d.data, "error");
                }
            }, function (e) {
                swal("Error", e, "error");
            })
        }
        function setFormat(lst) {
            for (var i in lst) {
                if (lst[i].TipoServicio !== 'ReporteDiario') {
                    lst[i].NumeroReporte = "N°         " + lst[i].NumeroReporte;
                    lst[i].Repuestos = JSON.parse(lst[i].Repuestos);
                    lst[i].FallaDetectada = JSON.parse(lst[i].FallaDetectada);
                    lst[i].MedidasAplicadas = JSON.parse(lst[i].MedidasAplicadas);
                    lst[i].EstadoFinal = JSON.parse(lst[i].EstadoFinal);
                }
            }
            return lst;
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                $scope.Encabezado = e.data;
            });
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        function _init() {
            var obj = {
                SedeId: $stateParams.sede_id,
                ServicioId: $stateParams.servicio_id,
                Year: $stateParams.year,
                Mes: $stateParams.mes
            };
            GetReporteByServicioId(obj);
            GetEncabezado();
            GetEmpresa();
        }
        $scope.isInArray = function (txt, lst) {
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
        }
        //</editor-fold>
//        printDiv();
        _init();
    }]);



