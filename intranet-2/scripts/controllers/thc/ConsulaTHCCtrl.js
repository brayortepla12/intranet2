app.controller('ConsultaTHCCtrl', ["$scope", "$rootScope", "HistoriaService", "NotasService",
    function ($scope, $rootScope, HistoriaService, NotasService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Notas = [];
        $scope.TrazabilidadH = [];
        $scope.NoAdmision = {};
        $scope.NOADMISION = "";
        $scope.Historia = null;
        $scope.HTab = 1;
        $scope.items = Array.from(Array(30), (x, index) => {
            return {Item: index + 1};
        });
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.GetTrazabilidad = () => {
            HistoriaService.GetTrazabilidadByHistoriaId($scope.Historia.HistoriaId).then((d) => {
                $scope.TrazabilidadH = d.data;
            });
        };
        $scope.ConsultarHistoria = () => {
            if ($scope.NoAdmision) {
                $scope.Historia = null;
                HistoriaService.GetHistoriaById($scope.NoAdmision.originalObject.HistoriaId).then((d) => {
                    if (typeof d.data != "string") {
                        $scope.Historia = d.data;
                        GetNotas();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        $scope.setHTab = (d) => {
            $scope.HTab = d;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetNotas() {
            NotasService.GetNotas($scope.Historia.HistoriaId).then((d) => {
                $scope.Notas = d.data;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        //</editor-fold>
        function _init() {
        }
        _init();
    }]);