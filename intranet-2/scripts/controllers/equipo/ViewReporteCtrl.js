app.controller('ViewReporteCtrl', ["$scope", "$rootScope", "$stateParams", 'ReporteService','EncabezadoService','EmpresaService',
    function ($scope, $rootScope, $stateParams, ReporteService, EncabezadoService, EmpresaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.reporte = {};
        $scope.Empresa = "";
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.OpenArchivo = function(url){
            window.open('http://192.168.8.125:8080/Polivalente/upload_files/' + url, '_blank');
        };
        $scope.Imprimir = function(){
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
                        ReporteRecibeId: $rootScope.username.PersonaId
                    };
                    ReporteService.FirmarReporte(obj).then(function (d) {
                        if (typeof d.data == "object") {
                            $scope.reporte.Estado = "Firmado";
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
                timeout: 2000,
                title: null,
                doctype: '<!doctype html>'
            });

        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetReporteByReporteId(Id){
            ReporteService.GetReporteById(Id).then(function(d){
                console.log(d.data)
                if (typeof d.data != "string") {
                    $scope.reporte = d.data[0];
                    $scope.reporte.NumeroReporte = "N°         " + $scope.reporte.NumeroReporte;
                    $scope.reporte.Repuestos = JSON.parse($scope.reporte.Repuestos);
                    $scope.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
                    $scope.reporte.MedidasAplicadas = JSON.parse($scope.reporte.MedidasAplicadas);
                    $scope.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
                }else{
                    swal("Error", d.data, "error");
                }
            },function(e){
                swal("Error", e, "error");
            })
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                $scope.Encabezado = e.data;
            });
        }
        function _init() {
            GetEmpresa();
            GetEncabezado();
            GetReporteByReporteId($stateParams.reporte_id);
        }
        $scope.isInArray = function(txt, lst){
            for (var i in lst) {
                if (txt == lst[i]) {
                    return true;
                }
            }
            return false;
        };
        $scope.getDiffFalla = function(lst){
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



