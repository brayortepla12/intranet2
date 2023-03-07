'use strict';
app.controller('PantallaAmbuCtrl', ["$scope", "$rootScope", "ReporteAmbulanciaService",
    function ($scope, $rootScope,ReporteAmbulanciaService) {
        $scope.Moviles = [];
        $scope.Movil = {};
        $scope.ShowInformacion = (o) => {
            $scope.Movil = angular.copy(o);
            $('#VerInformacionModal').modal('show');
        };
        function __init__(){
            ReporteAmbulanciaService.getCronograma().then((d) => {
                console.log(d.data);
                $scope.Moviles = d.data;
            });
        }
        __init__();
    }]);

