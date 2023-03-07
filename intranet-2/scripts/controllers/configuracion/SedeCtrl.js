'use strict';
app.controller('SedeCtrl', ["$scope", "$rootScope", "SedeService", "$filter",
    function ($scope, $rootScope, SedeService, $filter) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.Sedes = [];
        $scope.Sede = {
            Nombre: null,
            Correo: null,
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.selected = {};
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetSede() {
            SedeService.getAllSede().then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "-SedeId");
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        $scope.ResetForm = function () {
            $scope.Sede = {
                Nombre: null,
                Correo: null,
                CreatedBy: $rootScope.username.NombreUsuario
            };
        };
        $scope.GuardarSede = function () {
            var data = {
                Sede: JSON.stringify([$scope.Sede])
            };
            SedeService.PostSede(data).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.Sede = {
                        Nombre: null,
                        Correo: null,
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $('#SedeModal').modal('hide');
                    GetSede();
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
            GetSede();
        }
        //<editor-fold defaultstate="collapsed" desc="Codigo para el editar en linea">
        $scope.getTemplate = function (item) {
            if (item.SedeId === $scope.selected.SedeId) {
                return '/polivalente/sede.edit.html';
            } else
                return '/polivalente/sede.display.html';
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
                Sede: JSON.stringify([$scope.select]),
                ID: $scope.select.SedeId
            };
            SedeService.PutSede(obj).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.select = {};
                    $scope.selected = {};
                    GetSede();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };
        //</editor-fold>
    }]);





