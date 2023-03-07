app.controller('ListadoPermisoCtrl', ["$scope", "$rootScope", "PermisoCTService", "PersonaService",
    function ($scope, $rootScope, PermisoCTService, PersonaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.simpleTableOptions = {};
        $scope.cargado = false;
        $scope.Hoy = moment();
        $scope.FechaMin = moment($scope.Hoy).subtract(90, 'hours');
        $scope.FechaMax = moment($scope.Hoy).add(150, 'hours');

        $scope.Permiso = {
            PersonaId: '',
            Motivo: '',
            Cual: '',
            Otro: false,
            LiderId: $rootScope.username.UserId,
            CreatedBy: $rootScope.username.NombreCompleto,
            FechaInicio: moment().format('YYYY-MM-DD HH:mm:ss'),
            FechaFin: moment(moment().format('YYYY-MM-DD HH:mm:ss')).add(2, 'hours'),
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Eventos">
        $scope.CambiaFechaInicio = () => {
            $scope.FechaMax = moment($scope.Permiso.FechaInicio).add(250, 'hours');
        };
        $scope.RevocarPermiso = (PermisoId) => {
            let obj = {
                Permiso: JSON.stringify({
                    PermisoId: PermisoId,
                    ModifiedBy: $rootScope.username.NombreCompleto
                })
            };
            PermisoCTService.DeletePermiso(obj).then((d) => {
                console.log(d.data);
                GetPermisoByLiderId();
            });
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">

        function GetPermisos() {
            $scope.cargado = false;
            PermisoCTService.getPermisos().then((d) => {
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'PermisoId'},
                        {mData: 'CreatedAt'},
                        {mData: 'Nombres'},
                        {mData: 'Motivo'},
                        {mData: 'Cual'},
                        {mData: 'FechaInicio'},
                        {mData: 'FechaFin'},
                        {mData: 'VBGestionHumana'},
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
                $scope.simpleTableOptions.data = SetFormat(d.data);
                $scope.cargado = true;
            });
        }
        //</editor-fold>

        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '';
                lst[i].IsConsumido = lst[i].UsoTotal > 0 ? 'Si' : 'No';
                lst[i].VBGestionHumana = lst[i].VBGestionHumana ? 'Aprobado' : 'Pendiente';
                if (lst[i].VBGestionHumana === 'Pendiente') {
                    lst[i].Opciones = '<a class="btn  btn-danger btn-xs icon-only white" onclick=\"angular.element(this).scope().RevocarPermiso(' + lst[i].PermisoId + ')\"><i class="fa fa-trash"></i></a>';
                }
            }
            return lst;
        }
        function __init__() {
            GetPermisos();
        }
        __init__();
    }]);



