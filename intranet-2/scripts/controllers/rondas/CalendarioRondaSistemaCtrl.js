app.controller('CalendarioRondaSistemaCtrl', ["$scope", "$rootScope", "$crypto", "$state", "$stateParams", "RondaSistemaServicioService", "SesionService",
    function ($scope, $rootScope, $crypto, $state, $stateParams, RondaSistemaServicioService, SesionService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.calendarView = 'month';
        $scope.viewDate = new Date();
        $scope.events = [];
        $scope.RondaId = $stateParams.RondaId;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.eventCreate = function (date) {
            var Mes = (date.getMonth() + 1) >= 10 ? date.getMonth() + 1 : "0" + (date.getMonth() + 1);
            var Dia = date.getDate() >= 10 ? date.getDate() : "0" + (date.getDate());
            var Fecha = date.getFullYear() + "-" + Mes + "-" + Dia;
            var encrypted = $crypto.encrypt(Fecha, 'Franklin Ospino');
            var url = $state.href("ronda.Crear_detalleRonda_sistema", { RondaId: $scope.RondaId ,token: encrypted});
            window.open(url, '_blank'); /// consultar por ID
//            swal({
//                title: "¿Que desea hacer?",
//                text: "",
//                type: "warning",
//                showCancelButton: true,
//                confirmButtonClass: "btn-success",
//                confirmButtonText: "Asignar Tareas",
//                cancelButtonText: "Hacer Seguimiento",
//                closeOnConfirm: true,
//                closeOnCancel: true
//            },
//                    function (isConfirm) {
//                        console.log(isConfirm)
//                        if (isConfirm) {
//                            var url = $state.href("ronda.tarea", {token: encrypted});
//                            window.open(url, '_blank'); /// consultar por ID
//                        } else {
//                            var url = $state.href("ronda.seguimiento", {token: encrypted});
//                            window.open(url, '_blank'); /// consultar por ID
//                        }
//                    });
        };
        $scope.changeMode = function (mode) {
            $scope.calendarView = mode;
        };
        $scope.Imprimir = function () {
            $scope.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        //</editor-fold>
        function printDiv() {
            $("#Planilla").print({
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
                $scope.ToPrint = false;
                $scope.$apply();
            }, 1000);
        }
        function GetDetallesRondas() {
            RondaSistemaServicioService.getRondaSistemaServicioByUsuarioId_Ronda($rootScope.username.UserId, $scope.RondaId).then(function (c) {
                for (var i in c.data) {
                    $scope.events.push({
                        title: "Detalle Ronda N° " + c.data[i].DetalleRondaId,
                        start: new Date(toDate(c.data[i].Fecha)),
                        end: new Date(toDate(c.data[i].Fecha)),
                        allDay: true
                    });
                }
                console.log($scope.events);
            });
        }

        function toDate(dateStr) {
            var parts = dateStr.split("-");
            return new Date(parts[0], parts[1] - 1, parts[2]);
        }

        function _init() {
            GetDetallesRondas();
        }
        _init();
    }]);




