'use strict';
app.controller('ProveedorCtrl', ["$scope", "$rootScope", "ProveedorService", function ($scope, $rootScope, ProveedorService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.Proveedors = [];
        $scope.Proveedor = {
            Nombre: null,
            Documento: null,
            TipoDocumento: null,
            Telefono: null,
            Direccion: null,
            Email: null,
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.selected = {};
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetProveedor() {
            ProveedorService.getAllProveedor().then(function (c) {
                $scope.Proveedors = c.data;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">

        $scope.GuardarProveedor = function () {
            var data = {
                Proveedor: JSON.stringify([$scope.Proveedor])
            };
            ProveedorService.PostProveedor(data).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.Proveedor = {
                        Nombre: null,
                        Documento: null,
                        TipoDocumento: null,
                        Telefono: null,
                        Direccion: null,
                        Email: null,
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $('#ProveedorModal').modal('hide');
                    GetProveedor();
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
            GetProveedor();
        }

        //<editor-fold defaultstate="collapsed" desc="Codigo para el editar en linea">
        $scope.getTemplate = function (item) {
            if (item.ProveedorId === $scope.selected.ProveedorId) {
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
                Proveedor: JSON.stringify([$scope.select]),
                ID: $scope.select.ProveedorId
            };
            ProveedorService.PutProveedor(obj).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.select = {};
                    $scope.selected = {};
                    GetProveedor();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };
        //</editor-fold>


    }]);


