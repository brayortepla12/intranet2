app.controller('ReferenciaCtrl', ["$scope", "$rootScope", "ReferenciaService",
    function ($scope, $rootScope, ReferenciaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Mes = moment().format('M');
        $scope.Year = moment().format('YYYY');
        $scope.Estado = 'Activo';
        $scope.simpleTableOptions = {};
        $scope.cargado = false;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Eventos">
        $scope.GetReferencias = () => {
            GetReferencias();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetReferencias() {
            $scope.cargado = false;
            $scope.simpleTableOptions.data = [];
            ReferenciaService.getReferenciaByMes($scope.Year, $scope.Mes).then((d) => {
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'idReferencia'},
                        {mData: 'Fecha'},
                        {mData: 'Admision'},
                        {mData: 'Paciente'},
                        {mData: 'Origen'},
                        {mData: 'Destino'},
                        {mData: 'TipoTraslado'},
                        {mData: 'Movil'},
                        {mData: 'Conductor'},
                        {mData: 'Auxiliar'},
                        {mData: 'EPS'},
                        {mData: 'Variable'},
                        {mData: 'FechaInicioTraslado'},
                        {mData: 'FechaFinTraslado'},
                        {mData: 'Diagnostico'},
                        {mData: 'Observaciones'},
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
                $scope.simpleTableOptions.data = SetFormat(d.data);
                $scope.cargado = true;
            });
        }
        
        
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '';
                lst[i].Fecha = lst[i].Fecha.date;
                lst[i].FechaInicioTraslado = lst[i].FechaInicioTraslado ? lst[i].FechaInicioTraslado.date : "";
                lst[i].FechaFinTraslado = lst[i].FechaFinTraslado ? lst[i].FechaFinTraslado.date : "";
            }
            return lst;
        }
        //</editor-fold>
        function __init__() {
            $scope.GetReferencias();
        }
        __init__();
    }]);


