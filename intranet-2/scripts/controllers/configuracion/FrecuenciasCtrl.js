'use strict';
app.controller('FrecuenciaCtrl', ["$scope", "$rootScope", "FrecuenciaService", function ($scope, $rootScope, FrecuenciaService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.Frecuencias = [];
        $scope.Frecuencia = {
            Nombre: null,
            NumeroMeses: null,
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.selected = {};
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetFrecuencia() {
            FrecuenciaService.getAllFrecuencia().then(function (c) {
                $scope.Frecuencias = c.data;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">

        $scope.GuardarFrecuencia = function () {
            var data = {
                Frecuencia: JSON.stringify([$scope.Frecuencia])
            };
            FrecuenciaService.PostFrecuencia(data).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.Frecuencia = {
                        Nombre: null,
                        NumeroMeses: null,
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $('#FrecuenciaModal').modal('hide');
                    GetFrecuencia();
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
            GetFrecuencia();
        }


        //<editor-fold defaultstate="collapsed" desc="Codigo para el editar en linea">
        $scope.getTemplate = function (item) {
            if (item.FrecuenciaMantenimientoId === $scope.selected.FrecuenciaMantenimientoId) {
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
                Frecuencia: JSON.stringify([$scope.select]),
                ID: $scope.select.FrecuenciaMantenimientoId
            };
            FrecuenciaService.PutFrecuencia(obj).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.select = {};
                    $scope.selected = {};
                    GetFrecuencia();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };
        //</editor-fold>


    }]);





