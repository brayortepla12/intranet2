app.controller('AlertaCtrl', ["$scope", "$rootScope", "RondaService", "MantenimientoPreventivoService", "MantenimientoPreventivoSistemaService", "HojaVidaService", "HojaVidaSistemaService", "SolicitudService",
    "ReporteService", "SedeService",
    function ($scope, $rootScope, RondaService, MantenimientoPreventivoService, MantenimientoPreventivoSistemaService, HojaVidaService, HojaVidaSistemaService, SolicitudService, ReporteService, SedeService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope;
        vm.FiltroPreventivo = null;
        $rootScope.AlertaTareas = [];
        $rootScope.AlertasMantenimientoPreventivos = [];
        $rootScope.AlertasMantenimientoPreventivosSistemas = [];
        $rootScope.NEquipos = [];
        $rootScope.NEquiposSistemas = [];
        $rootScope.Tareas = [];
        vm.Suma = 0;
        vm.data = [];
        vm.labels = [];
        vm.Computadores = 0;
        vm.Impresora = 0;
        vm.op = "";
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        vm.Imprimir = function (div) {
//            vm.ToPrint = true;
            printDiv(div);
        };
        vm.SetOption = (op) => {
            vm.op = op;
            switch (op) {
                case 'Sistemas':
                    CountTOTALEquiposSistema();
                    GetAlertaMantenimientoPreventivosSistemas();
                    CountComputadoresSistema();
                    CountImpresorasSistema();
//                    GetNEquiposSistemas();
                    break;
                case 'Polivalente':
                    GetAlertaMantenimientoPreventivos();
//                    GetNEquipos();
                    CountHojaVida();
                    break;
            }
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetTareas() {
            RondaService.GetTareas($rootScope.username.UserId).then(function (t) {
                $rootScope.Tareas = t.data;
            });
        }

        function GetAlertaMantenimientoPreventivosSistemas() {
            MantenimientoPreventivoSistemaService.GetAllAlertas($rootScope.username.UserId).then(function (m) {
                $rootScope.AlertasMantenimientoPreventivosSistemas = m.data[0];
            });
        }
        function GetAlertaMantenimientoPreventivos() {
            MantenimientoPreventivoService.GetAllAlertas($rootScope.username.UserId).then(function (m) {
                $rootScope.AlertasMantenimientoPreventivos = m.data[0];
            });
        }
        function GetNEquipos() {
            MantenimientoPreventivoService.GetNEquiposByServicio($rootScope.username.UserId).then(function (e) {
//                $rootScope.NEquipos = e.data;
//                GetSum(e.data);
                vm.suma = e.data[0].Total;
            });
        }
        function GetNEquiposSistemas() {
            MantenimientoPreventivoSistemaService.GetNEquiposByServicio($rootScope.username.UserId).then(function (e) {
//                $rootScope.NEquiposSistemas = e.data;
//                GetSum(e.data);
                vm.suma = e.data[0].Total;
            });
        }
        function CountHojaVida() {
            HojaVidaService.CountHojaVidas($rootScope.username.UserId).then(function (c) {
                vm.TotalEquipos = c.data;
            });
        }
        function CountTOTALEquiposSistema() {
            HojaVidaSistemaService.CountHojaVidas($rootScope.username.UserId).then(function (c) {
                vm.TotalEquiposSistemas = c.data;
            });
        }
        function CountComputadoresSistema() {
            HojaVidaSistemaService.CountComputadores($rootScope.username.UserId).then(function (c) {
                vm.Computadores = c.data;
            });
        }
        function CountImpresorasSistema() {
            HojaVidaSistemaService.CountImpresoras($rootScope.username.UserId).then(function (c) {
                vm.Impresoras = c.data;
            });
        }
        function CountSolicitudes() {
            SolicitudService.CountSolicitudes($rootScope.username.UserId).then(function (c) {
                vm.TotalSolicitudes = c.data;
            });
        }
        function GetEstadisticas() {
            var date = new Date();
            ReporteService.GetEstadisticas(date.getFullYear(), date.getMonth() + 1).then(function (e) {
                Build_Grafica(e.data);
            });
        }
        function GetSede() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                vm.FiltroPreventivo = c.data[0].SedeId;
                vm.Sedes = c.data;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function Build_Grafica(lst) {
            // alert(JSON.stringify(lst));
            for (var i in lst) {
                vm.data.push(lst[i].Cantidad);
                vm.labels.push(lst[i].TipoServicio);
            }
//            setOptions();
        }
        function printDiv(div) {
            $("#" + div).print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 750,
                title: null,
                doctype: '<!doctype html>'
            });

            setTimeout(function () {
                vm.ToPrint = false;
                vm.$apply();
            }, 1000);
        }

        function setOptions() {
            vm.options = {
                tooltipEvents: [],
                // showAllTooltips: true,
                // tooltipCaretSize: 0,
                // onAnimationComplete: function () {
                //     this.showTooltip(this.segments, true);
                // },,
                // legend: {
                //     display: true,
                //     // labels: { fontColor: '#FFCE56'},//yellow
                // },
                // scales: {
                //     xAxes: [{
                //         ticks: {
                //             beginAtZero: true
                //         }
                //     }]
                // },
                tooltips: {
                    enabled: false
                },
                animation: {
                    duration: 600,
                    onComplete: function () {
                        var chartInstance = this.chart,
                                ctx = chartInstance.ctx;
                        ctx.textAlign = 'center';
                        ctx.fillStyle = "rgba(0, 0, 0, 1)";
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = dataset.data[index];
                                ctx.fillText(data, bar._model.x, bar._model.y + 1); //-5
                            });
                        });
                    }
                },
                scales:
                        {
                            yAxes: [
                                // {
                                //     display: false,
                                // }
                                {
                                    // scaleLabel: { display: false, labelString: 'Max 72' },
                                    // position: 'left', id: 'y-axis-1',type: 'linear',
                                    display: false,
                                    ticks: {min: 0, beginAtZero: true, stepSize: 5, max: getMaxOfArray(vm.data)}
                                }
                            ],
                            xAxes: [{
                                    ticks: {
                                        stepSize: 1,
                                        min: 0,
                                        autoSkip: false
                                    }
                                }]
                        }
            };
        }
        function GetSum(lst) {
            vm.Suma = 0;
            for (var i in lst) {
                vm.Suma += lst[i].Cantidad;
            }
        }
        //</editor-fold>
        function _init() {
//            swal("ADVERTENCIA", "El dia de hoy 05-02-2019 se estará realizando un trabajo de mantenimiento entre las 8PM a 10PM, por lo cual la plataforma estará fuera de servicio.", "warning");
            vm.Usuario = $rootScope.username;
            GetSede();
            if (vm.Usuario.IsSistemas) {
                vm.SetOption('Sistemas');
            } else if (vm.Usuario.IsPolivalente) {
                vm.SetOption('Polivalente');
            }
        }
        _init();
    }]);




