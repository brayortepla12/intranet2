app.controller('ActividadMesTmCtrl', ["$scope", "$rootScope", "MaternaService", "DepartamentoService", "MunicipioService", "TmEventoService",
    function ($scope, $rootScope, MaternaService, DepartamentoService, MunicipioService, TmEventoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Departamentos = [];
        $scope.Municipios = [];
        $scope.DepartamentoId = 11;
        $scope.MunicipioId = 456;
        $scope.Mes = moment().format('M');
        $scope.Year = moment().format('YYYY');
        $scope.Maternas = [];
        $scope.MaternasMes = [];
        $scope.cargado = false;
        $scope.cargado_reg = false;
        $scope.Imprimir_flag = false;
        $scope.NombreCiudad = "";
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">     
        $scope.Imprimir = () => {
            $scope.Imprimir_flag = true;
            printDiv();
        };
        $scope.ConsultarPorFechas = function () {
            for (let i in $scope.Municipios) {
                if($scope.Municipios[i].CiudadId == $scope.MunicipioId){
                    $scope.NombreCiudad = $scope.Municipios[i].Ciudad;
                }
            }
            GetActividad();
        };
        $scope.ChangeDepartamento = function () {
            GetMunicipioByDepartamento();
        };
        $scope.ShowEventos = (MaternaId) => {
            getEventosByMaternaId(MaternaId);
        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetActividad() {
            $scope.cargado = false;
            $scope.cargado_reg = false;
            console.log($scope.LiderId);
            MaternaService.GetActividadMes($scope.Year, $scope.Mes, $scope.MunicipioId).then(function (c) {
                console.log(c.data);
                $scope.Maternas = c.data;
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'MaternaId'},
                        {mData: 'TotalEntregado'},
                        {mData: 'FechaEvento'},
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
                let arr = SetFormat(c.data);
                $scope.simpleTableOptions.data = arr;
                $scope.Maternas = arr;
                $scope.cargado = true;
            }).then(() => {
                MaternaService.GetMaternaRegistradasByMes($scope.Year, $scope.Mes, $scope.MunicipioId).then(function (m) {

                    $scope.cargado_reg = false;
                    $scope.simpleTableOptions2 = {
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
                    let arr = SetFormat(m.data);
                    $scope.simpleTableOptions2.data = arr;
                    $scope.MaternasMes = arr;
                    $scope.cargado_reg = true;
                });
            });
        }
        function GetDepartamentos() {
            DepartamentoService.GetDepartamentos().then(function (d) {
                $scope.Departamentos = d.data;
            }).then(function () {
                GetMunicipioByDepartamento();
            });
        }
        function GetMunicipioByDepartamento() {
            MunicipioService.GetMunicipiosByDepartamentoId($scope.DepartamentoId).then(function (m) {
                $scope.Municipios = m.data;
                $scope.MunicipioId = m.data[0].CiudadId;
            }).then(() => {
                $scope.ConsultarPorFechas();
            });
        }
        function getEventosByMaternaId(MaternaId) {
            $scope.Eventos = [];
            TmEventoService.getEventosByMaternaId(MaternaId).then((e) => {
                $scope.Eventos = e.data;
                $('#EventosModal').modal('show');
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                if (lst[i].HaveParto == 1) {
                    lst[i].HaveParto = '<img src="/intranet-2/public_html/image/bebe.png" width="20"/>';
                } else if (lst[i].HaveParto == 2) {
                    lst[i].HaveParto = '<img src="/intranet-2/public_html/image/uncheck.png" width="20"/>';
                } else {
                    lst[i].HaveParto = '...';
                }
                lst[i].Opciones = '<a class="btn  btn-primary btn-xs icon-only white" onclick=\"angular.element(this).scope().ShowEventos(' + lst[i].MaternaId + ')\"/><i class="fa fa-eye"></i></a>';
            }
            return lst;
        }
        function printDiv() {
            $("#impresion_actividad").print({
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
                $scope.Imprimir_flag = false;
                $scope.$apply();
            }, 1000);
        }
        //</editor-fold>
        function _init() {
            GetDepartamentos();
        }
        _init();
    }]);









