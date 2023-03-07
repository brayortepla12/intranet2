app.controller('RondaVerificacionCtrl', ["$scope", "$rootScope", "$crypto", "$state", "RondaVerificacionService", "TipoMedicamentoService",
    function ($scope, $rootScope, $crypto, $state, RondaVerificacionService, TipoMedicamentoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.calendarView = 'month';
        $scope.viewDate = new Date();
        $scope.events = [];
        $scope.RondasSeleccionadas = [];
        $scope.Rondas = [];
        $scope.selected = null;
        $scope.encrypted = null;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.GotoCreate = function () {
            var url = $state.href("cm.CrearRondaVerificacion", {token: $scope.encrypted});
            window.open(url, '_blank'); /// consultar por ID
        };
        $scope.GotoCreateLoteado = () => {
            var url = $state.href("cm.CrearRondaVerificacionLoteado", {token: $scope.encrypted});
            window.open(url, '_blank'); /// consultar por ID
        };
        $scope.GotoRotular = function () {
            var url = $state.href("cm.RotularMedicamentos", {token: $scope.encrypted});
            window.open(url, '_blank'); /// consultar por ID
        };
        $scope.eventCreate = function (date) {
            $scope.RondasSeleccionadas = [];
            var mes = date.getMonth() + 1 < 10 ? "0" + (date.getMonth() + 1) : date.getMonth() + 1;
            var dia = date.getDate() < 10 ? "0" + date.getDate() : date.getDate();
            var Fecha = date.getFullYear() + "-" + mes + "-" + dia;
            $scope.encrypted = $crypto.encrypt(Fecha, 'Franklin Ospino');
//            swal({
//                title: "¿Que desea hacer?",
//                text: "",
//                type: "warning",
//                showCancelButton: true,
//                confirmButtonClass: "btn-primary",
//                confirmButtonText: "Crear Ronda",
//                cancelButtonText: "Ver Ronda",
//                closeOnConfirm: true,
//                closeOnCancel: true
//            }, function (isConfirm) {
//                console.log(isConfirm)
//                if (isConfirm) {
//                    var url = $state.href("cm.CrearRondaVerificacion", {token: encrypted});
//                    window.open(url, '_blank'); /// consultar por ID
//                } else {
//                    var url = $state.href("cm.generar_excel", {token: encrypted});
//                    window.open(url, '_blank'); /// consultar por ID
//                }
//            });
//            console.log($scope.events);
            for (var i in $scope.Rondas) {
                if ($scope.Rondas[i].Fecha == Fecha) {
                    $scope.RondasSeleccionadas.push($scope.Rondas[i]);
                }
            }
            console.log($scope.RondasSeleccionadas)
            if(date <= new Date()){
                $('#RondasModal').modal('show');
            }
        };
        $scope.UpdateRondaVerificacionId = function(TipoMedicamentoId, RondaVerificacionId, TipoRonda){
            var url = $state.href("cm.UpdateRondaVerificacion", {token: $scope.encrypted, TipoMedicamentoId: TipoMedicamentoId, RondaVerificacionId: RondaVerificacionId, FechaSeleccionada: $scope.encrypted, TipoRonda: TipoRonda});
            window.open(url);
        };
        $scope.PreviewRondaVerificacionId = function(TipoMedicamentoId, RondaVerificacionId, TipoRonda){
            var url = $state.href("cm.generar_excel", {TipoMedicamentoId: TipoMedicamentoId, RondaVerificacionId: RondaVerificacionId, FechaSeleccionada: $scope.encrypted, TipoRonda: TipoRonda});
            window.open(url);
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
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetRondas() {
            RondaVerificacionService.GetAll_lite($rootScope.username.UserId).then(function (c) {
                for (var i in c.data) {
                    $scope.Rondas = angular.copy(c.data);
                    $scope.events.push({
                        title: c.data[i].TipoRonda + " N° " + c.data[i].RondaVerificacionId,
                        start: new Date(toDate(c.data[i].Fecha.split(" ")[0])),
                        end: new Date(toDate(c.data[i].Fecha.split(" ")[0])),
                        allDay: true
                    });
                }
            });
        }
        function GetTipoMedicamentos() {
            $scope.TipoMedicamentos = [];
            TipoMedicamentoService.getTiposMedicamentos().then(function (c) {
                $scope.TipoMedicamentos = c.data;
            });
        }
        //</editor-fold>

        

        function toDate(dateStr) {
            var parts = dateStr.split("-");
            return new Date(parts[0], parts[1] - 1, parts[2]);
        }

        function _init() {
            GetRondas();
            GetTipoMedicamentos();
            let host = window.location.hostname;
            if(host === '192.168.9.139'){
                swal("ATENCIÓN", "ESTAS EN MODO PRUEBAS", "warning");
                $scope.PRUEBAS = true;
            }
        }
        _init();
    }]);




