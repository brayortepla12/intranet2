app.controller('VerCunaCtrl', ["$scope", "$stateParams", 'ReporteService', 'TokenService', '$state', 'CunaService',
    function ($scope, $stateParams, ReporteService, TokenService, $state, CunaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Rtsp = "";
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetCunaByToken() {
            CunaService.GetCunaByToken($stateParams.token).then(function (d) {
                if (typeof d.data !== "string") {
                    console.log(d.data);
                    $scope.Rtsp = d.data[0].Rtsp;
                    vxgplayer('vxg_media_player_3').src($scope.Rtsp);
                    setTimeout(function () {
                        vxgplayer('vxg_media_player_3').play();
                    }, 2000)
                } else {
                    $state.go("login");
                }
            });
        }
        function isValidToken(Token) {
            var obj = {
                TokenCuna: Token
            }
            TokenService.isValidToken(obj).then(function (d) {
                if (typeof d.data == "object") {
                    $scope.EmailUsuario = d.data.sub;
                    console.log(d.data)
                    GetCunaByToken();
                } else {
                    $state.go("login");
                }
            });
        }
        function _init() {
            isValidToken($stateParams.token);

        }
        //</editor-fold>
//        printDiv();
        _init();
    }]);



