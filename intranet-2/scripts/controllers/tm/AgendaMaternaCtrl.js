app.controller('AgendaMaternaCtrl', ["$scope", "$rootScope", "MaternaService", "TmLiderService", "TmEventoService",
    function ($scope, $rootScope, MaternaService, TmLiderService, TmEventoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Mes = moment().format('M');
        $scope.Year = moment().format('YYYY');
        var ayer = new Date();
        $scope.Eventos = [];
        $scope.LiderId = "TODOS";
        ayer.setHours(0, 0, 0, 0);
        $scope.Dates = {startDate: moment().startOf('month'), endDate: moment().endOf('month')};
        $scope.ranges = {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract('days', 1), moment().subtract('days', 1)],
            'Ultimos 7 dias': [moment().subtract('days', 7), moment()],
            'Ultimos 30 dias': [moment().subtract('days', 30), moment()],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')]
        };
        $scope.cargado = false;
        $scope.UMaterna = {
            Nombres: "",
            Documento: "",
            Telefono: "",
            DepartamentoId: 11,
            FechaUltimaRegla: "",
            EdadUltimaEcografia: "",
            FechaProbableParto: "",
            MunicipioId: "",
            EPSId: null,
            LiderId: "",
            EdadGestional: null,
            ModifiedBy: $rootScope.username.NombreCompleto
        };
        
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">     
        $scope.ConsultarPorFechas = function () {
            let From = `${$scope.Year}-${$scope.Mes}-01`;
            let To = `${$scope.Mes == 12 ? parseInt($scope.Year) + 1 : $scope.Year}-${$scope.Mes == 12 ? 1 : parseInt($scope.Mes) + 1}-01`;
            GetAgendaMaterna(From, To);
        };
        $scope.ShowEventos = (MaternaId) => {
            getEventosByMaternaId(MaternaId);
        };
       
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetAgendaMaterna(From, To) {
            $scope.cargado = false;
            console.log($scope.LiderId);
            MaternaService.getAgendaMaterna($scope.LiderId, From, To).then(function (c) {
                console.log(c.data);
                $scope.Maternas = c.data;
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'MaternaId'},
                        {mData: 'FechaRegistro'},
                        {mData: 'Nombres'},
                        {mData: 'Ciudad'},
                        {mData: 'FechaProbableParto'},                        
                        {mData: 'Telefono'},
                        {mData: 'Lider'},
                        {mData: 'TelefonoLider'},
                        {mData: 'HaveParto'},
                        {mData: 'Opciones'},
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
        function getEventosByMaternaId(MaternaId) {
            $scope.Eventos = [];
            TmEventoService.getEventosByMaternaId(MaternaId).then((e) => {
                $scope.Eventos = e.data;
                $('#EventosModal').modal('show');
            });
        }
        function GetLideres() {
            TmLiderService.getLideres().then(function (c) {
                if (c.data.length > 0 && typeof c.data !== "string") {
                    $scope.Lideres = c.data;
                }
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                if (lst[i].HaveParto == 1) {
                    lst[i].HaveParto = '<img src="/intranet-2/public_html/image/bebe.png" width="20"/>';
                }else if(lst[i].HaveParto == 2){
                    lst[i].HaveParto = '<img src="/intranet-2/public_html/image/uncheck.png" width="20"/>';
                }else{
                    lst[i].HaveParto = '...';
                }
                lst[i].Opciones = '<a class="btn  btn-primary btn-xs icon-only white" onclick=\"angular.element(this).scope().ShowEventos(' + lst[i].MaternaId + ')\"/><i class="fa fa-eye"></i></a>';
            }
            return lst;
        }
        //</editor-fold>
        function _init() {
            GetLideres();
            $scope.ConsultarPorFechas();
        }
        _init();
    }]);






