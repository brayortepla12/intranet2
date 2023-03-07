app.controller('GestionPermisoCtrl', ["$scope", "$rootScope", "PermisoCTService", "PersonaService",
    function ($scope, $rootScope, PermisoCTService, PersonaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Mes = moment().format('M');
        $scope.Year = moment().format('YYYY');
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
        $scope.GetPermisos = () => {
            GetPersonasByLider();
        };
        $scope.CambiaFechaInicio = () => {
            $scope.FechaMax = moment($scope.Permiso.FechaInicio).add(250, 'hours');
        };
        $scope.OpenCrearModal = () => {
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
            $('#PermisoModal').modal('show');
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
        $scope.CrearPermiso = () => {
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {


                let obj = {
                    Permiso: JSON.stringify($scope.Permiso)
                };
                PermisoCTService.postPermiso(obj).then((d) => {
                    if (typeof d.data != 'string' && d.data.length > 0) {
                        swal("Enhorabuena", "Se ha guardado los datos con exito", "success");
                        GetPermisoByLiderId();
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
                        $('#PermisoModal').modal('hide');
                    }
                });
            }
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetPersonasByLider() {
            PersonaService.getPersonaByLider($rootScope.username.UserId).then((d) => {
                console.log(d.data);
                $scope.Personas = d.data;
            }).then(() => {
                GetPermisoByLiderId();
            });
        }

        function GetPermisoByLiderId() {
            $scope.cargado = false;
            PermisoCTService.getPermisoByLiderId($rootScope.username.UserId, $scope.Mes, $scope.Year).then((d) => {
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
                        {mData: 'IsConsumido'},
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
            GetPersonasByLider();
        }
        __init__();
    }]);



