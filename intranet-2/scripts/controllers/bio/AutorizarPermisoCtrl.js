app.controller('AutorizarPermisoCtrl', ["$scope", "$rootScope", "PermisoCTService", "EmpresaService",
    function ($scope, $rootScope, PermisoCTService, EmpresaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Bandera_btn = false;
        $scope.Permisos = [];
        $scope.CodigoTarjeta = '';
        $scope.Persona = {};
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Eventos">
        $scope.BuscarPermisos = () => {
            $scope.Permisos = [];
            PermisoCTService.getPermisoByCodigoTarjeta($scope.CodigoTarjeta).then((d) => {
                console.log(d.data);
                $scope.Persona = d.data[0];
            }).then(() => {
                getPermisoByPersonaId_Fecha($scope.Persona.PersonaId, $scope.Persona.FechaInicio, $scope.Persona.FechaFin);
            });
        };

        $scope.Autorizar = () => {
            if (!$scope.Bandera_btn) {
                let obj = {
                    Permiso_autorizar: $scope.Persona.PermisoId,
                    UsuarioGHId: $rootScope.username.UserId,
                    ValidarPermisos: JSON.stringify($scope.Permisos)
                };
                $scope.Bandera_btn = true;
                PermisoCTService.AutorizarPermiso(obj).then((d) => {
                    $scope.Bandera_btn = false;
                    if (typeof d.data != 'string') {
                        swal("Enhorabuena", "Se ha guardado los datos con exito", "success");
                        $scope.CodigoTarjeta = '';
                        $scope.Persona = {};
                        $scope.Permisos = [];
                    }
                }, () => {
                    $scope.Bandera_btn = false;
                });
            }
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
        }
        setInterval(function () {
            $("#CodigoTarjeta").focus();
        }, 1000);
        __init__();
    }]);






