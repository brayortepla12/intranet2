app.controller('ListadoReportesCtrl', ["$scope", "$rootScope", "ReporteService",
    function ($scope, $rootScope, ReporteService) {
        $scope.Reportes = [];
        $rootScope.Reportes2 = [];
        function GetReportesByEquipoId() {
            if ($rootScope.ficha) {
                ReporteService.GetReporteByEquipoId($rootScope.ficha.HojaVidaId).then(function (r) {
                    $scope.Reportes = r.data;
                    $rootScope.Reportes2 = Crearpaquetes($scope.Reportes);
                });
            } else if ($rootScope.HojaVida) {
                ReporteService.GetReporteByEquipoId($rootScope.HojaVida.HojaVidaId).then(function (r) {
                    $scope.Reportes = r.data;
                    $rootScope.Reportes2 = Crearpaquetes($scope.Reportes);
                });
            }
        }
        function _init() {
            GetReportesByEquipoId();
        }
        function Crearpaquetes(lst) {
            var paquete = {
                pack: []
            };
            var size = 14;
            for (var i = 0; i < lst.length; i += size) {
                var smallarray = lst.slice(i, i + size);
                // do something with smallarray
                paquete.pack.push(smallarray);
            }
            return paquete;
        }
        _init();
    }]);



