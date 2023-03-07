'use strict';
app.controller('CronogramaAmbulanciaCtrl', ["$scope", "$rootScope", "HojaVidaAmbulanciaService", "ReporteAmbulanciaService",
    function ($scope, $rootScope, HojaVidaAmbulanciaService, ReporteAmbulanciaService) {
        $scope.Moviles = [];
        $scope.Movil = {};
        $scope.HojaVidaId = "";
        $scope.ChangeMovil = () => {
            console.log($scope.HojaVidaId);
            GetCronogramaByHojaVidaId();
        };
        $scope.Cronograma = [];
        $scope.Intervalos = [];
        function GetHojaDeVidas() {
            HojaVidaAmbulanciaService.GetHojaVidaALL().then((d) => {
                console.log(d.data);
                $scope.Moviles = d.data;
                $scope.HojaVidaId = d.data[0].HojaVidaId;
                GetCronogramaByHojaVidaId();
            });
        }
        function GetCronogramaByHojaVidaId() {
            ReporteAmbulanciaService.GetCronogramaByEquipoId($scope.HojaVidaId).then((r) => {
                console.log(r.data);
                $scope.Cronograma = SetCronograma(r.data);
            });
        }

        function SetCronograma(lst) {
            $scope.Cronograma = [];
            $scope.Intervalos = [];
            let Desde = 0, Hasta = 0;
            for (let i in lst) {
                if ($scope.Intervalos.length === 0) {
                             console.log(lst[i].Reportes);
                    for (var j = 0; j < lst[i].Reportes.length; j++) {
                        if(lst[i].Reportes[j].length > 0){
                            Desde = (lst[i].Reportes[j].KmActual > 1000) ? lst[i].Reportes[j].KmActual - 100000 / 1000 : lst[i].Reportes[j].KmActual;
                            Hasta = (lst[i].Reportes[j].KmActual > 1000) ? lst[i].Reportes[j].KmActual + 100000 / 1000 : lst[i].Reportes[j].KmActual + 1000000;
                            for (let k = Desde; k <= Hasta; k += 5) {
                                $scope.Intervalos.push(k);
                            }
                        }
                    }
                }
            }
            return lst;
        }
        function __init__() {
            GetHojaDeVidas();
        }
        __init__();
    }]);

