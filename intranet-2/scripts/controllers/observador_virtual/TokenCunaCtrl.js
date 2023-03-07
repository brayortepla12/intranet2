'use strict';
app.controller('TokenCunaCtrl', ["$scope", "$rootScope", "TokenCunaService", "$filter", "CunaService", "$state",
    function ($scope, $rootScope, TokenCunaService, $filter, CunaService, $state) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.TokenCunas = [];
        $scope.Cunas = [];
        $scope.TokenCuna = {
            Nombre: null,
            Email: null,
            Dias: 1,
            CunaId: 1,
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.selected = {};
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetTokenCuna() {
            TokenCunaService.getAllTokenCuna().then(function (c) {
                console.log(c.data)
                $scope.TokenCunas = $filter("orderBy")(c.data, "-TokenCunaId");
            });
        }
        function GetCuna() {
            CunaService.getAllCuna().then(function (c) {
                $scope.Cunas = $filter("orderBy")(c.data, "Nombre");
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        $scope.ResetForm = function () {
            $scope.TokenCuna = {
                Nombre: null,
                Email: null,
                Dias: 1,
                CunaId: 1,
                CreatedBy: $rootScope.username.NombreUsuario
            };
        };
        $scope.GuardarTokenCuna = function () {
            var data = {
                TokenCuna: JSON.stringify([$scope.TokenCuna])
            };
            TokenCunaService.PostTokenCuna(data).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.TokenCuna = {
                        Nombre: null,
                        Email: null,
                        Dias: 1,
                        CunaId: 1,
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $('#TokenCunaModal').modal('hide');
                    GetTokenCuna();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };

        $scope.Imprimir = function () {
            $.print("#printable");
        };
        $scope.VerCuna = function (o) {
            console.log(o);
            var url = $state.href("ver_cuna", {token: o.Token});
            window.open(url,'_blank');
        };
        //</editor-fold>

        function _init() {
            GetTokenCuna();
            GetCuna();
        }

        //<editor-fold defaultstate="collapsed" desc="Codigo para el editar en linea">
        $scope.getTemplate = function (item) {
            if (item.TokenCunaId === $scope.selected.TokenCunaId) {
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
                TokenCuna: JSON.stringify([$scope.select]),
                ID: $scope.select.TokenCunaId
            };
            TokenCunaService.PutTokenCuna(obj).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.select = {};
                    $scope.selected = {};
                    GetTokenCuna();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };
        //</editor-fold>


    }]);





