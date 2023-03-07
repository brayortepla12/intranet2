'use strict';
app.controller('PermisoCtrl', ["$scope", "$rootScope", "PermisoService", "ModuloService",
    function ($scope, $rootScope, PermisoService, ModuloService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.Permisos = [];
        $scope.Modulos = [];
        $scope.Permiso = {
            Nombre: null,
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.selected = {};
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetModulos() {
            ModuloService.getAllModulo().then(function (m) {
                console.log(m.data);
                $scope.Modulos = m.data;
            });
        }
        function GetPermiso() {
            PermisoService.getAllPermiso().then(function (c) {
                $scope.Permisos = c.data;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">

        $scope.GuardarPermiso = function () {
            if (!$scope.c.$valid) {
                angular.element("[name='" + $scope.c.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {
                var data = {
                    Permiso: JSON.stringify([$scope.Permiso])
                };
                PermisoService.PostPermiso(data).then(function (d) {
                    if (typeof d.data !== "object") {
                        swal("Hubo un Error", d.data, "error");
                    } else {
                        swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                        $scope.Permiso = {
                            Nombre: null,
                            CreatedBy: $rootScope.username.NombreUsuario
                        };
                        $('#PermisoModal').modal('hide');
                        GetPermiso();
                    }
                }, function (e) {
                    swal("Hubo un Error", e, "error");
                });
            }

        };

        $scope.Imprimir = function () {
            $.print("#printable");
        };
        //</editor-fold>

        function _init() {
            GetPermiso();
            GetModulos();
        }


        //<editor-fold defaultstate="collapsed" desc="Codigo para el editar en linea">
        $scope.getTemplate = function (item) {
            if (item.PermisoId === $scope.selected.PermisoId) {
                return 'edit';
            } else
                return '/polivalente/permisos.display.html';
        };
        $scope.editObj = function (item) {


            $scope.ItemA = "";
            $scope.select = item;
            $scope.selected = angular.copy(item);

        };
        $scope.reset = function () {
            $scope.selected = {};
        };
        $scope.Actualizar = function () {
            $scope.select.ModifiedBy = $rootScope.username.NombreCompleto;
            var obj = {
                Permiso: JSON.stringify([$scope.select]),
                ID: $scope.select.PermisoId
            };
            PermisoService.PutPermiso(obj).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.select = {};
                    $scope.selected = {};
                    GetPermiso();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };
        //</editor-fold>


    }]);





