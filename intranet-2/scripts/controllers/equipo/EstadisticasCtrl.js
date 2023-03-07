app.controller('EstadisticasCtrl', ["$scope", '$rootScope', "EmpresaService", "ReporteService",
    function ($scope, $rootScope, EmpresaService, ReporteService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        // colores
        $scope.Colors3 = ["#5B54B2", "#FFCBAB", "#9A91FF", "#6ECC60", "#41B230"];
        $scope.Colors2 = ["#00BDFF", "#143CCC", "#FF7B2E", "#CC3D14", "#3D5199"];
        $scope.colors = ['#ff6384', '#45b7cd', "#17B264", "#E2ABFF", "#91FFC8", "#CCA760", "#B28630"];
        $scope.ReportesSede = [];
        $scope.ReporteSedeLabel = [];
        $scope.ReporteSedeSerie = [];
        $scope.ReportesSedeT = [];
        $scope.ReportesServicio = [];
        $scope.ReporteServicioLabel = [];
        $scope.ReporteServicioSerie = [];
        $scope.ReportesServicioT = [];

        $scope.EstadoSedeLabel = [];
        $scope.EstadoSedeSerie = [];
        $scope.EstadosSedeT = [];
        $scope.EstadosServicio = [];
        $scope.EstadoServicioLabel = [];
        $scope.EstadoServicioSerie = [];
        $scope.EstadosServicioT = [];

        $scope.FallaSede = [];
        $scope.FallaSedeLabel = [];
        $scope.FallaSedeSerie = [];
        $scope.FallaSedeT = [];
        $scope.FallaServicio = [];
        $scope.FallaServicioLabel = [];
        $scope.FallaServicioSerie = [];
        $scope.FallasServicioT = [];
        
        $scope.opciones = {
            scales:
                    {
                        xAxes: [{
                                ticks: {
                                    fontSize: 25
                                }
                            }],
                        yAxes: [{
                                ticks: {
                                    fontSize: 25
                                }
                            }]
                    },

        };
        var ayer = new Date();
        ayer.setHours(0, 0, 0, 0);
        $scope.Dates = {startDate: new Date(ayer.setDate(ayer.getDate() - 2)), endDate: new Date()};
        $scope.ranges = {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract('days', 1), moment().subtract('days', 1)],
            'Ultimos 7 dias': [moment().subtract('days', 7), moment()],
            'Ultimos 30 dias': [moment().subtract('days', 30), moment()],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')]
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Imprimir = function () {
            printDiv();
        };
        $scope.ConsultarEstadisticas = function () {
            ReporteService.GetALLEstadisticas(moment($scope.Dates.startDate).format('YYYY-MM-DD H:mm:ss'),
                    moment($scope.Dates.endDate).format('YYYY-MM-DD H:mm:ss'), $rootScope.username.UserId).then(function (e) {
                        console.log(e.data)
                SetReportesSede(e.data.TipoReporteSede);
                SetReportesServicio(e.data.TipoReporteServicio);
                SetEstadoSede(e.data.EstadoFinalSede);
//                SetEstadoServicio(e.data.EstadoFinalServicio);
                SetFallaSede(e.data.FallaDetectadaSede);
            });
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function printDiv() {
            var canvas1 = document.getElementById('RSede');
            document.getElementById("print_sede").removeChild(canvas1);
            var img = new Image;  /// give an id so we can remove it in next step}
            img.id = 'canvas1';
            img.onload = print;           /// onload handler triggers your print method
            img.src = canvas1.toDataURL(); /// set canvas image as source
            document.getElementById("print_sede").appendChild(img);

            var canvas2 = document.getElementById('RSede2');
            document.getElementById("print_sede2").removeChild(canvas2);
            var img = new Image;  /// give an id so we can remove it in next step
            img.id = 'canvas2';
            img.onload = print;           /// onload handler triggers your print method
            img.src = canvas2.toDataURL(); /// set canvas image as source
            document.getElementById("print_sede2").appendChild(img);

            var canvas3 = document.getElementById('Estado');
            document.getElementById("print_Estado").removeChild(canvas3);
            var img = new Image;  /// give an id so we can remove it in next step
            img.id = 'canvas3';
            img.onload = print;           /// onload handler triggers your print method
            img.src = canvas3.toDataURL(); /// set canvas image as source
            document.getElementById("print_Estado").appendChild(img);

            var canvas4 = document.getElementById('Estado2');
            document.getElementById("print_Estado2").removeChild(canvas4);
            var img = new Image;  /// give an id so we can remove it in next step
            img.onload = print;           /// onload handler triggers your print method
            img.id = 'canvas4';
            img.src = canvas4.toDataURL(); /// set canvas image as source
            document.getElementById("print_Estado2").appendChild(img);

            var canvas5 = document.getElementById('Falla');
            document.getElementById("print_Falla").removeChild(canvas5);
            var img = new Image;  /// give an id so we can remove it in next step
            img.onload = print;           /// onload handler triggers your print method
            img.id = 'canvas5';
            img.src = canvas5.toDataURL(); /// set canvas image as source
            document.getElementById("print_Falla").appendChild(img);

            var canvas6 = document.getElementById('Falla2');
            document.getElementById("print_Falla2").removeChild(canvas6);
            var img = new Image;  /// give an id so we can remove it in next step
            img.onload = print;           /// onload handler triggers your print method
            img.id = 'canvas6';
            img.src = canvas6.toDataURL(); /// set canvas image as source
            document.getElementById("print_Falla2").appendChild(img);
            $("#PaginaGrafica").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 950,
                title: null,
                doctype: '<!doctype html>'
            });

            setTimeout(function () {
                $scope.ToPrint = false;
                $scope.$apply();
            }, 1000);
        }
        function SetReportesSede(lst) {
            $scope.ReportesSede = [];
            $scope.ReporteSedeLabel = ["Calibración", "Preventivos", "Correctivos", "Instalación", "Traslado", "TOTAL"];
            $scope.ReporteSedeSerie = [];
            $scope.ReportesSedeT = [];

            for (var i in lst) {
                $scope.ReporteSedeSerie.push(lst[i].Sede);
                $scope.ReportesSede.push([lst[i].NCalibracion, lst[i].NPreventivos, lst[i].NCorrectivos, lst[i].NInstalacion, lst[i].NTraslados, lst[i].TotalMantenimientos]);
                $scope.ReportesSedeT.push([lst[i].TotalCalibracion, lst[i].TotalPreventivo, lst[i].TotalCorrectivo, lst[i].TotalInstalacion, lst[i].TotalTraslado, lst[i].TotalRepuesto]);
            }
        }
        function SetReportesServicio(lst) {
            $scope.ReportesServicio = [];
            $scope.ReporteServicioLabel = ["Calibración", "Preventivos", "Correctivos", "Instalación", "Traslado"];
            $scope.ReporteServicioSerie = [];
            $scope.ReportesServicioT = [];
            $scope.ReportesServicio = Crearpaquetes(lst);
        }
        function SetEstadoSede(lst) {
            $scope.EstadosSede = [];
            $scope.EstadoSedeLabel = ["Esp. Repuestos", "F. Rangos", "F. Servicio", "Correctamente", "Rec. Baja", "Retirado", "TOTAL"];
            $scope.EstadoSedeSerie = [];
            $scope.EstadoSedeT = [];

            for (var i in lst) {
                $scope.EstadoSedeSerie.push(lst[i].Sede);
                $scope.EstadosSede.push([lst[i].EsperaRep, lst[i].FueraRangos, lst[i].FueraServ, lst[i].FuncCorrectamente, lst[i].RecBaja, lst[i].RetiradoServ, lst[i].TotalEstados]);
                $scope.EstadoSedeT.push([lst[i].TEsperaRep, lst[i].TFueraRangos, lst[i].TFueraServ, lst[i].TFuncCorrectamente, lst[i].TRecBaja, lst[i].TRetiradoServ, lst[i].TotalMantenimientos]);
            }
        }
        function SetEstadoServicio(lst) {
            $scope.EstadosServicio = [];
            $scope.EstadoServicioLabel = ["Esp. Repuestos", "F. Rangos", "F. Servicio", "Correctamente", "Rec. Baja", "Retirado", "TOTAL"];
            $scope.EstadoServicioSerie = [];
            $scope.EstadoServicioT = [];
            for (var i in lst) {
                $scope.EstadoServicioSerie.push(lst[i].Servicio);
                $scope.EstadosServicio.push([lst[i].EsperaRep, lst[i].FueraRangos, lst[i].FueraServ, lst[i].FuncCorrectamente, lst[i].RecBaja, lst[i].RetiradoServ, lst[i].TotalEstados]);
                $scope.EstadoServicioT.push([lst[i].TEsperaRep, lst[i].TFueraRangos, lst[i].TFueraServ, lst[i].TFuncCorrectamente, lst[i].TRecBaja, lst[i].TRetiradoServ, lst[i].TotalMantenimientos]);
            }
        }

        function SetFallaSede(lst) {
            $scope.FallaSede = [];
            $scope.FallaSedeLabel = ["Accesorio", "Desgaste", "Mal Uso", "Otro"];
            $scope.FallaSedeSerie = [];

            $scope.FallaSedeT = [];
            for (var i in lst) {
                $scope.FallaSedeSerie.push(lst[i].Sede);
                $scope.FallaSede.push([lst[i].Accesorio, lst[i].Desgaste, lst[i].MalUso, lst[i].Otro]);
                $scope.FallaSedeT.push([lst[i].TAccesorio, lst[i].TDesgaste, lst[i].TMalUso, lst[i].TOtro]);
            }

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
        //</editor-fold>
        function _init() {
            $scope.ConsultarEstadisticas();
            GetEmpresa();
        }
        _init();
    }]);




