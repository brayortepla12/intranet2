'use strict';
app.controller('CunaCtrl', ["$scope", "$rootScope", "CunaService", "$filter", "$state","$crypto",
    function ($scope, $rootScope, CunaService, $filter, $state, $crypto) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.Cunas = [];
        $scope.Camas = [];
        $scope.Rtsp = "";
        $scope.Cuna = {
            Nombre: null,
            Rtsp: null,
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.selected = {};
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetCuna() {
            CunaService.getAllCuna().then(function (c) {
                $scope.Cunas = $filter("orderBy")(c.data, "-CunaId");
            });
        }
        function GetCunaKristalos(UserId) {
            CunaService.getAllCunaKristalos(UserId).then(function (c) {
                console.log(c.data);
                $scope.Camas = c.data;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        $scope.ResetForm = function () {
            $scope.Cuna = {
                Nombre: null,
                Rtsp: null,
                CreatedBy: $rootScope.username.NombreUsuario
            };
        };
        $scope.Change = function(){
            var Tipo =  ($scope.Cuna.Tipo ? $scope.Cuna.Tipo != '' ? "/" + $scope.Cuna.Tipo : "" : "");
            $scope.Cuna.Rtsp = "rtsp://" + $scope.Cuna.Usuario  + ":" + $scope.Cuna.Password + "@" + $scope.Cuna.Ip + ":" + $scope.Cuna.Port + Tipo + "/Streaming/channels/" + $scope.Cuna.Canal + "/";
            vxgplayer('vxg_media_player1').src($scope.Cuna.Rtsp);
        };
        $scope.VerCuna = function (item) {
            localStorage.setItem("Rtsp", $crypto.encrypt(item.Rtsp, 'Franklin Ospino'));
            var url = $state.href("ver_cuna_int");
            window.open(url,'_blank');
//            $('#VerCunaModal').modal('show');
//            console.log($scope.Rtsp);
//            setTimeout(function(){
//                vxgplayer('vxg_media_player2').src($scope.Rtsp);
//            },1000)
        };
        $scope.Previsualizar = function(){
            var Tipo =  ($scope.Cuna.Tipo ? $scope.Cuna.Tipo != '' ? "/" + $scope.Cuna.Tipo : "" : "");
            $scope.Cuna.Rtsp = "rtsp://" + $scope.Cuna.Usuario  + ":" + $scope.Cuna.Password + "@" + $scope.Cuna.Ip + ":" + $scope.Cuna.Port + Tipo + "/Streaming/channels/" + $scope.Cuna.Canal + "/";
            localStorage.setItem("Rtsp", $crypto.encrypt($scope.Cuna.Rtsp, 'Franklin Ospino'));
            var url = $state.href("ver_cuna_int");
            window.open(url,'_blank');
        };
        $scope.GuardarCuna = function () {
            
            var data = {
                Cuna: JSON.stringify([$scope.Cuna])
            };
            CunaService.PostCuna(data).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.Cuna = {
                        Nombre: null,
                        Rtsp: null,
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $('#CunaModal').modal('hide');
                    GetCuna();
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
            GetCuna();
            GetCunaKristalos($rootScope.username.UserId);
        }


        //<editor-fold defaultstate="collapsed" desc="Codigo para el editar en linea">
        $scope.getTemplate = function (item) {
            if (item.CunaId === $scope.selected.CunaId) {
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
                Cuna: JSON.stringify([$scope.select]),
                ID: $scope.select.CunaId
            };
            CunaService.PutCuna(obj).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.select = {};
                    $scope.selected = {};
                    GetCuna();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };
        //</editor-fold>


    }]);





