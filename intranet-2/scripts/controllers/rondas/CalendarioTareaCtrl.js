app.controller('CalendarioTareaCtrl', ["$scope", "$rootScope", "$crypto", "$state", "RondaService",
    function ($scope, $rootScope, $crypto, $state, RondaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.calendarView = 'month';
        $scope.viewDate = new Date();
        $scope.events = [];
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.eventCreate = function (date) {
            var Fecha = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            var encrypted = $crypto.encrypt(Fecha, 'Franklin Ospino');
            swal({
                title: "¿Que desea hacer?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Asignar Tareas",
                cancelButtonText: "Hacer Seguimiento",
                closeOnConfirm: true,
                closeOnCancel: true
            },
                    function (isConfirm) {
                        console.log(isConfirm)
                        if (isConfirm) {
                            var url = $state.href("polivalente.tarea", {token: encrypted});
                            window.open(url, '_blank'); /// consultar por ID
                        } else {
                            var url = $state.href("polivalente.seguimiento", {token: encrypted});
                            window.open(url, '_blank'); /// consultar por ID
                        }
                    });
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
        function GetRondas() {
            RondaService.GetAll_lite($rootScope.username.UserId).then(function (c) {
                for (var i in c.data) {
                    $scope.events.push({
                        title: "Ronda N° " + c.data[i].RondaId + " - " + c.data[i].Servicio,
                        start: new Date(toDate(c.data[i].Fecha.split(" ")[0])),
                        end: new Date(toDate(c.data[i].Fecha.split(" ")[0])),
                        allDay: true
                    });
                }
            });
        }

        function toDate(dateStr) {
            var parts = dateStr.split("-");
            return new Date(parts[0], parts[1] - 1, parts[2]);
        }

        function _init() {
            GetRondas();
        }
        _init();
    }]);




