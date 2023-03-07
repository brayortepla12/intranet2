app.controller('SolicitudAdminNavCtrl', ["$scope", "$rootScope", "$state", "ReporteService",
    function ($scope, $rootScope, $state, ReporteService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Back = function (s) {
            $state.go("polivalente.solicitudAdmin");
            $rootScope.View = false;
            $rootScope.solicitud = null;
        };
        
        $scope.BackInEquipoView = function (s) {
            $rootScope.ficha = null;
            $rootScope.ver_reportes = false;
            $state.go("polivalente.Listado_equipos");
        };
        $scope.ViewReportes = function () {
            $rootScope.ver_reportes = $rootScope.ver_reportes ? false : true;
        };
        $scope.CreateReporteManual = function (f) {
            $rootScope.HojaVida = null;
            $rootScope.HojaVidaRapida = angular.copy(f);
            console.log($rootScope.HojaVidaRapida)
            if (f.Servicio == "PLANTAS ELÉCTRICAS") {
                $state.go("polivalente.ReporteDiario");
            }else if(f.IsSistema){
                $state.go("sistemas.reporte_servicio_sistemas");
            }else{
                $('tooltip-inner').css('display', 'none');
                $state.go("polivalente.reporte_servicio");
            }
        };
        $scope.CreateReporteExterno = function (f) {
            $rootScope.HojaVida = null;
            $rootScope.HojaVidaRapida = angular.copy(f);
            $('tooltip-inner').css('display', 'none');
            $state.go("polivalente.reporte_escaneado");
        };
        $scope.Atras = function () {
            if($rootScope.HojaVidaRapida.IsSistema){
                $state.go('sistemas.ficha_tecnica_2', {HojaVidaId: $rootScope.HojaVidaRapida.HojaVidaId});
            }else{
                $state.go('polivalente.ficha_tecnica', {HojaVidaId: $rootScope.HojaVidaRapida.HojaVidaId});
            }
        };
        $scope.AddReporte = function () {
            if ($rootScope.solicitud.Estado === "Borrador") {
                swal("Notificación:", "Esta solicitud ya tiene un reporte a la espera de ser firmado por la persona que recibe.", "warning");
                ReporteService.GetReporteBySolicitudId($rootScope.solicitud.SolicitudId).then(function (d) {
                    $state.go("polivalente.ver_reporte", {reporte_id: d.data[0].ReporteId});
                });
            } else if ($rootScope.solicitud.Estado === "Completado") {
                ReporteService.GetReporteBySolicitudId($rootScope.solicitud.SolicitudId).then(function (d) {
                    $state.go("polivalente.ver_reporte", {reporte_id: d.data[0].ReporteId});
                });
            } else {
                if ($rootScope.solicitud.Estado === "Externo") {
                    swal("Notificación:", "Esta solicitud ya tiene un archivo externo asignado.", "warning");
                    ReporteService.GetReporteBySolicitudId($rootScope.solicitud.SolicitudId).then(function (d) {
                        console.log(d.data)
                        window.open('http://192.168.8.125:8080/Polivalente/upload_files/' + d.data[0].ReporteArchivo, '_blank');
                    });
                } else {
                    $state.go("polivalente.reporte_servicio_interno");
                }
                
            }
        };
        $scope.AddReporteExterno = function () {
            if ($rootScope.solicitud.Estado !== "Borrador" && $rootScope.solicitud.Estado !== "Completado") {
                if ($rootScope.solicitud.Estado !== "Externo") {
                    $state.go("polivalente.reporte_escaneado_interno");
                } else {
                    swal("Notificación:", "Esta solicitud ya tiene un archivo externo asignado.", "warning");
                    ReporteService.GetReporteBySolicitudId($rootScope.solicitud.SolicitudId).then(function (d) {
                        console.log(d.data)
                        window.open('http://192.168.8.125:8080/Polivalente/upload_files/' + d.data[0].ReporteArchivo, '_blank');
                    });
                }
            }
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        
        //</editor-fold>
    }]);
