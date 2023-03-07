app.controller('ListadoReportesSSTCtrl', ["$scope", "$rootScope", "ReporteSSTService",
    function ($scope, $rootScope, ReporteSSTService) {
        $scope.Reportes = [];
        $rootScope.Reportes2 = [];
        function GetReportesByEquipoId() {
            if ($rootScope.ficha) {
                ReporteSSTService.GetReporteByEquipoId($rootScope.ficha.HojaVidaId).then(function (r) {
                    console.log(r.data);
                    $scope.Reportes = r.data;
                    $rootScope.Reportes2 = Crearpaquetes($scope.Reportes);
                });
            } else if ($rootScope.HojaVida) {
                ReporteSSTService.GetReporteByEquipoId($rootScope.HojaVida.HojaVidaId).then(function (r) {
                    console.log(r.data);
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



