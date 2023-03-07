app.controller('GraficaHomeSistemasCtrl', ["$scope", "$rootScope", "$filter", "ReporteSistemaService",
    function ($scope, $rootScope, $filter, ReporteSistemaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope;
        vm.CuentaPreventivos = 0;
        vm.CuentaCorrectivos = 0;
        vm.TotalMantenimientos = 0;
        vm.CuentaInstalacion = 0;
        vm.CuentaRecarga = 0;
        vm.CuentaRedes = 0;
        vm.CuentaServicio = 0;
        var ayer = new Date();
        ayer.setHours(0, 0, 0, 0);
        vm.Dates = {startDate: new Date(ayer.setDate(ayer.getDate() - 7)), endDate: new Date()};
        vm.ranges = {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract('days', 1), moment().subtract('days', 1)],
            'Ultimos 7 dias': [moment().subtract('days', 7), moment()],
            'Ultimos 30 dias': [moment().subtract('days', 30), moment()],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')]
        };
        vm.dataset = [{data: [], label: 'fecha'}];
        vm.options = {
            yaxis: {
                mode: 'time',
                timeformat: "%Y/%m/%d",
                minTickSize: [1, "day"]
            },
            xaxis: {min: 1, minTickSize: 1},
            markings: [{yaxis: {from: vm.Dates.startDate, to: vm.Dates.endDate}, xaxis: {from: 0, to: vm.dataset[0].length + 1}}],
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
        vm.ConsultarPorFechas = function () {
            GetReportesBetweenFecha(moment(vm.Dates.startDate).format('YYYY-MM-DD H:mm:ss'), moment(vm.Dates.endDate).format('YYYY-MM-DD H:mm:ss'));
        };
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetReportesBetweenFecha(From, To) {
            ReporteSistemaService.GetReportesBetweenFecha(From, To, $rootScope.username.UserId).then(function (d) {
                buildGrafica($filter('orderBy')(d.data, "CreatedAt"));
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helper">
//        for (var i = 0; i < 14; i += 0.5) {
//            vm.dataset[0].data.push([i, Math.sin(i)]);
//        }
        function buildGrafica(lst) {
            vm.CuentaPreventivos = 0;
            vm.CuentaCorrectivos = 0;
            vm.TotalMantenimientos = 0;
            vm.CuentaInstalacion = 0;
            vm.CuentaRecarga = 0;
            vm.CuentaRedes = 0;
            vm.CuentaServicio = 0;
            vm.TotalMantenimientos = lst.length;
            vm.dataset = [{data: [], label: 'fecha'}];
            var cont = 1;
            for (var i in lst) {
                vm.dataset[0].data.push([cont, new Date(lst[i].CreatedAt)]);
                cont++;
                if (lst[i].TipoServicio === "PREVENTIVO") {
                    vm.CuentaPreventivos++;
                } else if (lst[i].TipoServicio === "CORRECTIVO") {
                    vm.CuentaCorrectivos++;
                } else if (lst[i].TipoServicio === "INSTALACION") {
                    vm.CuentaInstalacion++;
                } else if (lst[i].TipoServicio === "RECARGA") {
                    vm.CuentaRecarga++;
                } else if (lst[i].TipoServicio === "REDES") {
                    vm.CuentaRedes++;
                } else if (lst[i].TipoServicio === "SERVICIO") {
                    vm.CuentaServicio++;
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
        vm.onEventExampleHover = function (event, pos, item) {
            if (item) {
                var m = new Date(item.datapoint[1]);
                var dateString = m.getUTCFullYear() + "/" + (m.getUTCMonth() + 1) + "/" + m.getUTCDate();// + " " + m.getUTCHours() + ":" + m.getUTCMinutes() + ":" + m.getUTCSeconds();
                var tool = $("#tooltip").html(dateString)
                        .css({top: item.pageY + 5, left: item.pageX + 5})
                        .fadeIn(200);

            }
        };

        vm.LeaveGrafica = function () {
            setTimeout(function () {
                $("#tooltip").html("").css({top: -1, left: -1});
            }, 500);
        };
        //</editor-fold>
        function _init2() {
            vm.ConsultarPorFechas();
        }
        _init2();
    }]);






