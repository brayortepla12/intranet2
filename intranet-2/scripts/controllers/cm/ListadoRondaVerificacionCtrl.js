app.controller('ListadoRondaVerificacionCtrl', ["$scope", "$rootScope", "RondaVerificacionService",
    function ($scope, $rootScope, RondaVerificacionService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.cargado = false;
        $scope.simpleTableOptions = {};
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.ViewItem = function (i) {

        };
        $scope.Imprimir = function () {
            $scope.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        $scope.ImprimirBySERVICIO = function () {
            GetSede();
            $('#ServicioModal').modal('show');
            GetServicio();
        };
        $scope.ChangeSede = function () {
            $scope.ServicioId = null;
            GetServicio();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetRondaVerificacion() {
            $scope.cargado = false;
            RondaVerificacionService.getRondaVerificacion($rootScope.username.UserId).then(function (c) {
                console.log(c.data);
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'RondaVerificacionId'},
                        {mData: 'Sector'},
                        {mData: 'Fecha'},
                        {mData: 'CreatedAt'},
                        {mData: 'Opciones'}
                    ],
                    "searching": true,
                    "iDisplayLength": 25,
                    "language": {
                        "lengthMenu": "Mostrar _MENU_ registros por página",
                        "zeroRecords": " No hay Items Registrados ",
                        "infoFiltered": "(filtro de _MAX_ registros totales)",
                        "search": " Filtrar : ",
                        "oPaginate": {
                            "sPrevious": "Anterior",
                            "sNext": "Siguiente"
                        }
                    },
                    "aaSorting": []
                };
                $scope.simpleTableOptions.data = SetFormat(c.data);
                $scope.cargado = true;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '<a class="btn btn-success btn-xs icon-only white" href="/Polivalente/#/central_mezclas/ronda_verificacion/' + lst[i].RondaVerificacionId + '" target="_blank"' +
                        ' ><i class="fa fa-info-circle"></i></a>';

            }
            return lst;
        }
        function printDiv() {
            $("#dospaginas").print({
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
        //</editor-fold>
        function _init() {
            GetRondaVerificacion();
        }
        _init();
    }]);
