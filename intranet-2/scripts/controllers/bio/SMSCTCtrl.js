app.controller('SMSCTCtrl', ["$scope", "$rootScope", "SMSCTService",
    function ($scope, $rootScope, SMSCTService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Mes = moment().format('M');
        $scope.Year = moment().format('YYYY');
        $scope.SMSs = [];
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">     
        $scope.ConsultarPorFechas = function () {
            GetActividad();
        };
       
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetActividad() {
            $scope.cargado = false;
            console.log($scope.LiderId);
            SMSCTService.GetSMSMes($scope.Year, $scope.Mes).then(function (c) {
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'SMSId'},
                        {mData: 'NombresJefe'},
                        {mData: 'NombresColaborador'},
                        {mData: 'CreatedAt'},
                        {mData: 'Mensaje'},                        
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
                $scope.simpleTableOptions.data = c.data;
                $scope.cargado = true;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        
        //</editor-fold>
        function _init() {
            GetActividad();
        }
        _init();
    }]);









