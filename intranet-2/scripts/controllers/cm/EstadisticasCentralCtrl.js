app.controller('EstadisticasCentralCtrl', ["$scope", "$rootScope", "RondaVerificacionService",
    function ($scope, $rootScope, RondaVerificacionService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Mes = moment().format('M');
        $scope.Year = moment().format('YYYY');
        $scope.Tipo = 'UNIDOSIS';
        $scope.simpleTableOptions = {};
        $scope.cargado = false;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Eventos">
        $scope.getEstadisticas = () => {
            GetEstadisticas();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetEstadisticas() {
            $scope.cargado = false;
            $scope.simpleTableOptions.data = [];
            RondaVerificacionService.getEstadisticas($scope.Mes, $scope.Year, $scope.Tipo).then((d) => {
                $scope.simpleTableOptions = {
                    data: SetFormat(d.data),
                    aoColumns: [
                        {mData: 'Fecha'},
                        {mData: 'Nombre'},
                        {mData: 'Concentracion'},
                        {mData: 'NombreAbreviado'},
                        {mData: 'CantidadAPreparar'},
                        {mData: 'CantidadUtilizada'},
                        {mData: 'CodigoKrystalos'},
                        {mData: 'PrecioCompra'},
                        {mData: 'Total'},
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
                $scope.cargado = true;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function printDiv(id) {
            $(id).print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 1500,
                title: null,
                doctype: '<!doctype html>'
            });

            setTimeout(function () {
                $scope.ToPrint = false;
                $scope.$apply();
            }, 1000);
        }
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '';
                lst[i].Total = Math.round(parseFloat(lst[i].PrecioCompra) * lst[i].CantidadUtilizada);
            }
            return lst;
        }
        //</editor-fold>
        function __init__() {
            GetEstadisticas();
        }
        __init__();
    }]);



