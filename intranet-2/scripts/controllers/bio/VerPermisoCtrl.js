app.controller('VerPermisoCtrl', ["$scope", "$rootScope", "$stateParams", "PermisoCTService", "EmpresaService",
    function ($scope, $rootScope, $stateParams, PermisoCTService, EmpresaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.PermisoId = $stateParams.PermisoId;
        $scope.Permisos = [];
        $scope.Persona = {};
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Eventos">
        $scope.BuscarPermisos = () => {
            $scope.Permisos = [];
            PermisoCTService.getPermisoByPermisoId($scope.PermisoId).then((d) => {
                console.log(d.data);
                $scope.Persona = d.data[0];
            }).then(() => {
                getPermisoByPersonaId_Fecha($scope.Persona.PersonaId, $scope.Persona.FechaInicio, $scope.Persona.FechaFin);
            });
        };

        $scope.Autorizar = () => {
            let obj = {
                Permiso_autorizar: $scope.Persona.PermisoId,
                UsuarioGHId: $rootScope.username.UserId,
                ValidarPermisos: JSON.stringify($scope.Permisos)
            };
            PermisoCTService.AutorizarPermiso(obj).then((d) => {
                if (typeof d.data != 'string') {
                    swal("Enhorabuena", "Se ha guardado los datos con exito", "success");
                    $scope.CodigoTarjeta = '';
                    $scope.Persona = {};
                    $scope.Permisos = [];
                }
            });
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">

        function getPermisoByPersonaId_Fecha(PersonaId, FechaInicio, FechaFin) {
            PermisoCTService.getPermisoByPersonaId_Fecha(PersonaId, FechaInicio, FechaFin).then((d) => {
                $scope.Permisos = d.data;
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
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            $scope.BuscarPermisos();
        }
        __init__();
    }]);






