app.controller('AutorizarPermisoDocumentoCtrl', ["$scope", "$rootScope", "PermisoCTService", "EmpresaService",
    function ($scope, $rootScope, PermisoCTService, EmpresaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Documento = '';
        $scope.Persona = {};
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Eventos">
        $scope.BuscarPermisos = () => {
            PermisoCTService.getPermisoByDocumento($scope.Documento).then((d) => {
                console.log(d.data);
                $scope.Persona = d.data[0];
            });
        };

        $scope.Autorizar = () => {
            let obj = {
                Permiso_autorizar: $scope.Persona.PermisoId,
                UsuarioGHId: $rootScope.username.UserId
            };
            PermisoCTService.AutorizarPermiso(obj).then((d) => {
                if (typeof d.data != 'string') {
                    swal("Enhorabuena", "Se ha guardado los datos con exito", "success");
                    $scope.CodigoTarjeta = '';
                    $scope.Persona = {};
                }
            });
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">

        function GetPermisoByLiderId() {
            PermisoCTService.getPermisoByLiderId($rootScope.username.UserId).then((d) => {
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'PermisoId'},
                        {mData: 'CreatedAt'},
                        {mData: 'Nombres'},
                        {mData: 'Motivo'},
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


        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        //</editor-fold>

        function __init__() {
            GetEmpresa();
        }
        __init__();
    }]);