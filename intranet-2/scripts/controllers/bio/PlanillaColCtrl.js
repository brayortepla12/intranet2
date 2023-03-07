app.controller('PlanillaColCtrl', ["$scope", "$rootScope", "ColaboradoresService",
    function ($scope, $rootScope, ColaboradoresService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Mes = moment().format('M');
        $scope.Year = moment().format('YYYY');
        $scope.Dia = moment().format('DD');
        $scope.Tipo = "Entrada";
        $scope.TipoTurno = "";
        $scope.Listados = [];
        $scope.ListadosES = [];
        $scope.Colaboradores = [];
        $scope.Dias = [];
        //</editor-fold>
        $scope.getColaboradores = () => {
            $scope.Colaboradores = [];
            GetColaboradores();
        };

        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetColaboradores() {
            ColaboradoresService.getESColaboradores($scope.Year, $scope.Mes, $scope.Dia).then((d) => {
                console.log(d.data);
                $scope.Colaboradores = d.data;
            });
        }
        function SetDias() {
            $scope.Dias = [];
            let fin_mes = moment(`${$scope.Year}-${$scope.Mes}-1`).endOf('month').format('DD');
            console.log(fin_mes)
            for (var i = 1; i <= fin_mes; i++) {
                $scope.Dias.push(i + "");
            }
        }
        //</editor-fold>
        function __init__() {
            SetDias();
            GetColaboradores();
        }

        __init__();
    }]);



