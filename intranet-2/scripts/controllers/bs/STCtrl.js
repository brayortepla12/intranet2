app.controller('STCtrl', ["$scope", "$rootScope", "STService",
    function ($scope, $rootScope, STService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
//        ayer.setHours(0, 0, 0, 0);
        var Intervalo = null;
        $scope.FFrom = moment().subtract('hours', 1).format("YYYY-MM-DDTHH:mm:ss");
        $scope.FTo = moment().format("YYYY-MM-DDTHH:mm:ss");
        $scope.STs = [];
        $scope.colors = ['#ff6384', '#45b7cd', '#ff8e72'];
        $scope.labels = [];
        $scope.data = [];
        $scope.labels1 = [];
        $scope.data1 = [];
        $scope.labels2 = [];
        $scope.data2 = [];
        $scope.datasetOverride = [
//            {
//                label: "Bar chart",
//                borderWidth: 1,
//                type: 'bar'
//            },
            {
                label: "Temperatura °C Sensor 0",
                borderWidth: 3,
                hoverBackgroundColor: "rgba(255,99,132,0.4)",
                hoverBorderColor: "rgba(255,99,132,1)",
                type: 'line'
            }
        ];
        
        $scope.datasetOverride1 = [
//            {
//                label: "Bar chart",
//                borderWidth: 1,
//                type: 'bar'
//            },
            {
                label: "Temperatura °C Sensor 1",
                borderWidth: 3,
                hoverBackgroundColor: "rgba(255,99,132,0.4)",
                hoverBorderColor: "rgba(255,99,132,1)",
                type: 'line'
            }
        ];
        
        $scope.datasetOverride2 = [
//            {
//                label: "Bar chart",
//                borderWidth: 1,
//                type: 'bar'
//            },
            {
                label: "Temperatura °C Sensor 2",
                borderWidth: 3,
                hoverBackgroundColor: "rgba(255,99,132,0.4)",
                hoverBorderColor: "rgba(255,99,132,1)",
                type: 'line'
            }
        ];
        $scope.Options = {
            scales: {
                yAxes: [
                    {
                        id: 'y-axis-1',
                        type: 'linear',
                        display: true,
                        position: 'left'
                    }]
            }

        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Eventos">
        $scope.ConsultarNeveras = () => {
            $scope.STs = [];
            clearInterval(Intervalo);
            getST();
            Intervalo = setInterval(() => {
                $scope.FTo = moment().format("YYYY-MM-DDTHH:mm:ss");
                getST();
            }, 30000);
        };
        $scope.AnularIntervalo = () => {
            clearInterval(Intervalo);
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">

        function getST() {
            let f = $scope.FFrom.replace("T", " ");
            let t = $scope.FTo.replace("T", " ");
            $scope.labels = [];
            $scope.data = [];
            $scope.labels1 = [];
            $scope.data1 = [];
            $scope.labels2 = [];
            $scope.data2 = [];
            STService.getSTs(f, t).then((d) => {
                $scope.STs = d.data;
                // Sensor 0
                let sub_data = [];
                $scope.STs.Sensor0.map(s => {
                    $scope.labels.push(s.Fecha);
                    sub_data.push(s.Temperatura);
                });
                $scope.data.push(angular.copy(sub_data));
                // Sensor 1
                sub_data = [];
                $scope.STs.Sensor1.map(s => {
                    $scope.labels1.push(s.Fecha);
                    sub_data.push(s.Temperatura);
                });
                $scope.data1.push(angular.copy(sub_data));
                // Sensor 2
                sub_data = [];
                $scope.STs.Sensor2.map(s => {
                    $scope.labels2.push(s.Fecha);
                    sub_data.push(s.Temperatura);
                });
                $scope.data2.push(angular.copy(sub_data));
            });
        }
        //</editor-fold>

        function __init__() {
            getST();
            Intervalo = setInterval(() => {
                $scope.FTo = moment().format("YYYY-MM-DDTHH:mm:ss");
                getST();
            }, 30000);
        }
        __init__();
    }]);






