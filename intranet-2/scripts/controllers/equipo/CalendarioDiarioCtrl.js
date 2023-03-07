app.controller('CalendarioDiarioCtrl', ["$scope", "$rootScope", "ReporteService",
    function ($scope, $rootScope, ReporteService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.calendarView = 'month';
        $scope.viewDate = new Date();
        $scope.index = null;
        $scope.events = [];
        $scope.Usuarios = [];
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.changeMode = function (mode) {
            $scope.calendarView = mode;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetReportesDiarios() {
            ReporteService.GetALLReportesDiariosByUsuarioId($rootScope.username.UserId).then(function (d) {
                $scope.events = FormatData(GetDiffFechaWithActual(d.data));
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function getRandomColor(Sede) {
//            var letters = 'BCDEF'.split('');
//            var color = '#';
//            for (var i = 0; i < 6; i++) {
//                color += letters[Math.floor(Math.random() * letters.length)];
//            }
            if (Sede === "CIELD") {
                return "#3E94A9";
            } else if (Sede === "CSI") {
                return "#A9A03E";
            } else if (Sede === "Vencido") {
                return "#A90B00";
            }

        }
        function FormatData(lst) {
            for (var i in lst) {
                if (typeof lst[i].Fecha != "object") {
                    lst[i].startsAt = new Date(lst[i].Fecha + " 12:10:00");
                    lst[i].endsAt = new Date(lst[i].Fecha + " 12:10:00");
                } else {
                    lst[i].startsAt = lst[i].Fecha;
                    lst[i].endsAt = lst[i].Fecha;
                }

                lst[i].title = lst[i].Equipo + " -- " + lst[i].Serie + " -- " + lst[i].Sede;
                var color = getRandomColor(lst[i].Sede);
                lst[i].color = {// can also be calendarConfig.colorTypes.warning for shortcuts to the deprecated event types
                    primary: color, // the primary event color (should be darker than secondary)
                    secondary: color // the secondary event color (should be lighter than primary)
                };
                lst[i].actions = [{// an array of actions that will be displayed next to the event title
                        label: '<i class=\'glyphicon glyphicon-pencil\'></i>', // the label of the action
                        cssClass: 'edit-action', // a CSS class that will be added to the action element so you can implement custom styling
                        onClick: function (args) { // the action that occurs when it is clicked. The first argument will be an object containing the parent event
                            $scope.items = args.calendarEvent;
                            var url = window.location.href;
                            var arr = url.split("/");
                            var result = arr[0] + "//" + arr[2].replace("#", "");
                            if ($scope.items.ReporteId) {
                                window.open(result + "/Polivalente/#/mantenimiento/reporte_servicio/" + ($scope.items.ReporteId ? $scope.items.ReporteId : ""), "_blank");
                            } else {
                                window.open(result + "/Polivalente/#/mantenimiento/reporte_diario/" + ($scope.items.ReporteId ? $scope.items.ReporteId : ""), "_blank");
                            }

                        }
                    }];
            }
            return lst;
        }

        function GetDiffFechaWithActual(lst) {
            var mayor = new Date(lst[0].Fecha + " 00:01:00");
            var Fecha_Hoy = new Date();
            for (var i in lst) {
                if (new Date(lst[i].Fecha + " 00:01:00") > mayor) {
                    mayor = new Date(lst[i].Fecha + " 00:01:00");
                }
            }
            for (var i = addDays(mayor, 1); i <= Fecha_Hoy; i = addDays(i, 1)) {
                lst.push({
                    Sede: "Vencido",
                    Fecha: i,
                    Equipo: "No se ha realizado.",
                    Serie: "N/A",
                });
            }
            return lst;
        }

        function addDays(date, days) {
            var result = new Date(date);
            result.setDate(result.getDate() + days);
            return result;
        }
        //</editor-fold>
        function _init() {
            GetReportesDiarios();
        }


        _init();
    }]);




