app.controller('GraficaHomeCtrl', ["$scope", "$rootScope", "$filter", "ReporteService",
    function ($scope, $rootScope, $filter, ReporteService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.CuentaPreventivos = 0;
        $scope.CuentaCorrectivos = 0;
        $scope.TotalMantenimientos = 0;
        $scope.CuentaLlamado = 0;
        $scope.CuentaGarantia = 0;
        $scope.CuentaInfraestructura = 0;
        $scope.CuentaInstalacion = 0;
        var ayer = new Date();
        ayer.setHours(0, 0, 0, 0);
        $scope.Dates = {startDate: new Date(ayer.setDate(ayer.getDate() - 7)), endDate: new Date()};
        $scope.ranges = {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract('days', 1), moment().subtract('days', 1)],
            'Ultimos 7 dias': [moment().subtract('days', 7), moment()],
            'Ultimos 30 dias': [moment().subtract('days', 30), moment()],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')]
        };
        $scope.dataset = [{data: [], label: 'fecha'}];
        $scope.options = {
            yaxis: {
                mode: 'time',
                timeformat: "%Y/%m/%d",
                minTickSize: [1, "day"]
            },
            xaxis: {min: 1, minTickSize: 1},
            markings: [{yaxis: {from: $scope.Dates.startDate, to: $scope.Dates.endDate}, xaxis: {from: 0, to: $scope.dataset[0].length + 1}}],
            legend: {
                container: '#legend',
                show: true
            },
            series: {
                lines: {show: true},
                points: {show: true},
//                curvedLines: {
//                              apply: true,
//                              active: true,
//                              monotonicFit: true
//                     }
            },
            grid: {
                clickable: true,
                hoverable: true
            }
        };
        //</editor-fold>
        $scope.ConsultarPorFechas = function () {
            GetReportesBetweenFecha(moment($scope.Dates.startDate).format('YYYY-MM-DD H:mm:ss'), moment($scope.Dates.endDate).format('YYYY-MM-DD H:mm:ss'));
        };
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetReportesBetweenFecha(From, To) {
            ReporteService.GetReportesBetweenFecha(From, To, $rootScope.username.UserId).then(function (d) {
                buildGrafica($filter('orderBy')(d.data, "CreatedAt"));
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helper">
//        for (var i = 0; i < 14; i += 0.5) {
//            $scope.dataset[0].data.push([i, Math.sin(i)]);
//        }
        function buildGrafica(lst) {
            $scope.CuentaPreventivos = 0;
            $scope.CuentaCorrectivos = 0;
            $scope.TotalMantenimientos = 0;
            $scope.CuentaLlamado = 0;
            $scope.CuentaGarantia = 0;
            $scope.CuentaInfraestructura = 0;
            $scope.CuentaInstalacion = 0;
            $scope.TotalMantenimientos = lst.length;
            $scope.dataset = [{data: [], label: 'fecha'}];
            var cont = 1;
            for (var i in lst) {
                $scope.dataset[0].data.push([cont, new Date(lst[i].CreatedAt)]);
                cont++;
                if (lst[i].TipoServicio === "PREVENTIVO") {
                    $scope.CuentaPreventivos++;
                } else if (lst[i].TipoServicio === "CORRECTIVO") {
                    $scope.CuentaCorrectivos++;
                } else if (lst[i].TipoServicio === "LLAMADO DE EMERGENCIA") {
                    $scope.CuentaLlamado++;
                } else if (lst[i].TipoServicio === "GARANTIA") {
                    $scope.CuentaGarantia++;
                } else if (lst[i].TipoServicio === "INFRAESTRUCTURA") {
                    $scope.CuentaInfraestructura++;
                } else if (lst[i].TipoServicio === "INSTALACION") {
                    $scope.CuentaInstalacion++;
                }
            }
        }
        $("<div id='tooltip'></div>").css({
            position: "absolute",
            display: "none",
            border: "1px solid #fdd",
            padding: "2px",
            "background-color": "#fee",
            opacity: 0.80
        }).appendTo("body");
        $scope.onEventExampleHover = function (event, pos, item) {
            if (item) {
                var m = new Date(item.datapoint[1]);
                var dateString = m.getUTCFullYear() + "/" + (m.getUTCMonth() + 1) + "/" + m.getUTCDate();// + " " + m.getUTCHours() + ":" + m.getUTCMinutes() + ":" + m.getUTCSeconds();
                var tool = $("#tooltip").html(dateString)
                        .css({top: item.pageY + 5, left: item.pageX + 5})
                        .fadeIn(200);

            }
        };

        $scope.LeaveGrafica = function () {
            setTimeout(function () {
                $("#tooltip").html("").css({top: -1, left: -1});
            }, 500);
        };
        //</editor-fold>
        function _init() {
            $scope.ConsultarPorFechas();
        }
        _init();
    }]);






