app.controller('VerCunaIntCtrl', ["$scope", "$rootScope", '$crypto', 'TokenService', '$state', 'CunaService',
    function ($scope, $rootScope, $crypto, TokenService, $state, CunaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Rtsp = "";
        var os = navigator.platform;
        /*
         * platform.name; // 'IE'
         platform.version; // '10.0'
         platform.layout; // 'Trident'
         platform.os; // 'Windows Server 2008 R2 / 7 x64'
         platform.description; 
         */
        console.log(platform.os);

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetCuna() {
            var Rtsp = $crypto.decrypt(localStorage.getItem("Rtsp"), 'Franklin Ospino');
//            var player = vxgplayer('vxg_media_player_3');
            if (Rtsp) {
                $scope.Rtsp = Rtsp;
                var player = videojs('hls-example');
                player.play();
//                if (platform.os.version === "7") {
//                    localStorage.setItem("Rtsp", Rtsp);
//                    location.href = "/Polivalente/ver_cuna.html";
//                } else {
//                    player.src(Rtsp);
//                    setTimeout(function () {
//                        player.play();
//                    }, 3000);
//                }

            } else {
                $state.go("login");
            }
        }

        function _init() {
            GetCuna();
        }
        //</editor-fold>
//        printDiv();
        _init();
    }]);



