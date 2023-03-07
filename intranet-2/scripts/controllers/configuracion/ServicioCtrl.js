'use strict';
app.controller('ServicioCtrl', ["$scope", "$rootScope", "ServicioService", "SedeService", "$filter",
    function ($scope, $rootScope, ServicioService, SedeService, $filter) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.Servicios = [];
        $scope.Sedes = [];
        $scope.Servicio = {
            Nombre: null,
            Piso: null,
            SedeId: null,
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.selected = {};
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetServicio() {
            ServicioService.getAllServicio().then(function (c) {
                $scope.Servicios = Format($filter("orderBy")(c.data, "Nombre"));
            });
        }
        function GetSede() {
            SedeService.getAllSede().then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "-SedeId");
                GetServicio();

            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">

        $scope.GuardarServicio = function () {
            var data = {
                Servicio: JSON.stringify([$scope.Servicio])
            };
            ServicioService.PostServicio(data).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.Servicio = {
                        Nombre: null,
                        Piso: null,
                        SedeId: null,
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $('#ServicioModal').modal('hide');
                    GetServicio();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };

        $scope.Imprimir = function () {
            $.print("#printable");
        };

        //</editor-fold>
        function Format(lst) {
            for (var i in lst) {
                for (var k in $scope.Sedes) {
                    if ($scope.Sedes[k].SedeId == lst[i].SedeId) {
                        lst[i].NombreSede = $scope.Sedes[k].Nombre;
                    }
                }
            }
            return lst;
        }
        function _init() {
            GetSede();

        }


        //<editor-fold defaultstate="collapsed" desc="Codigo para el editar en linea">
        $scope.getTemplate = function (item) {
            if (item.ServicioId === $scope.selected.ServicioId) {
                return 'edit';
            } else
                return '/polivalente/servicios.display.html';
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
                Servicio: JSON.stringify([$scope.select]),
                ID: $scope.select.ServicioId
            };
            ServicioService.PutServicio(obj).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.select = {};
                    $scope.selected = {};
                    GetServicio();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };
        //</editor-fold>


    }]);


