'use strict';
app.controller('GrupoCtrl', ["$scope", "$rootScope", "GrupoService", "$filter",
    function ($scope, $rootScope, GrupoService, $filter) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.Grupos = [];
        $scope.Grupo = {
            Nombre: null,
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.selected = {};
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetGrupo() {
            GrupoService.getAllGrupo().then(function (c) {
                $scope.Grupos = $filter("orderBy")(c.data, "-GrupoId");
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        $scope.ResetForm = function () {
            $scope.Grupo = {
                Nombre: null,
                CreatedBy: $rootScope.username.NombreUsuario
            };
        };
        $scope.GuardarGrupo = function () {
            var data = {
                Grupo: JSON.stringify([$scope.Grupo])
            };
            GrupoService.PostGrupo(data).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.Grupo = {
                        Nombre: null,
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $('#GrupoModal').modal('hide');
                    GetGrupo();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };

        $scope.Imprimir = function () {
            $.print("#printable");
        };
        //</editor-fold>
        function _init() {
            GetGrupo();
        }
        //<editor-fold defaultstate="collapsed" desc="Codigo para el editar en linea">
        $scope.getTemplate = function (item) {
            if (item.GrupoId === $scope.selected.GrupoId) {
                return 'edit';
            } else
                return 'display';
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
            $scope.select.ModifiedBy = $rootScope.username.UserId;
            var obj = {
                Grupo: JSON.stringify([$scope.select]),
                ID: $scope.select.GrupoId
            };
            GrupoService.PutGrupo(obj).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.select = {};
                    $scope.selected = {};
                    GetGrupo();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };
        //</editor-fold>
    }]);





