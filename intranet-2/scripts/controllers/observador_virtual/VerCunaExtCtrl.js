app.controller('VerCunaExtCtrl', ["$scope", "$stateParams", 'ReporteService', 'TokenService', '$state', 'CunaService',
    function ($scope, $stateParams, ReporteService, TokenService, $state, CunaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Rtsp = "";
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetCunaByAdmision() {
            console.log($stateParams.admision)
            CunaService.getAllCunaByAdmision($stateParams.admision).then(function (d) {
                console.log(d.data)
                if (typeof d.data !== "string") {
                    $scope.Rtsp = d.data[0].Rtsp;
                    try {
                        vxgplayer('vxg_media_player_3').src($scope.Rtsp);
                        setTimeout(function () {
                            vxgplayer('vxg_media_player_3').play();
//                            var err = vxgplayer('vxg_media_player_3').error(); // get error code
//                            // example of handling by code error
//                            switch (err) {
//                                case 0:
//                                    // MEDIA_ERR_URL
//                                    swal("Error!", "MEDIA_ERR_URL", "error");
//                                    break
//                                case 1:
//                                    // MEDIA_ERR_NETWORK
//                                    swal("Error!", "MEDIA_ERR_NETWORK", "error");
//                                    break
//                                case 2:
//                                    // MEDIA_ERR_SOURCE
//                                    swal("Error!", "MEDIA_ERR_SOURCE", "error");
//                                    break
//                                case 3:
//                                    // MEDIA_ERR_CARRIER
//                                    swal("Error!", "MEDIA_ERR_CARRIER", "error");
//                                    break
//                                case 4:
//                                    // MEDIA_ERR_AUDIO
//                                    swal("Error!", "MEDIA_ERR_AUDIO", "error");
//                                    break
//                                case 5:
//                                    // MEDIA_ERR_VIDEO
//                                    swal("Error!", "MEDIA_ERR_VIDEO", "error");
//                                    break
//                                case 6:
//                                    // MEDIA_ERR_AUTHENTICATION
//                                    swal("Error!", "MEDIA_ERR_AUTHENTICATION", "error");
//                                    break
//                                case 7:
//                                    // MEDIA_ERR_BANDWIDTH
//                                    swal("Error!", "MEDIA_ERR_AUTHENTICATION", "error");
//                                    break
//                                case 8:
//                                    // MEDIA_ERR_EOF
//                                    swal("Error!", "MEDIA_ERR_EOF", "error");
//                                    break
//                                default:
//                                    // no error
//                                    swal("Error!", "Error no especificado. Por favor recargue la pagina.", "error");
//                            }
                        }, 3000)
                    } catch (e) {
                        swal("Error!", "Por favor recargue la pagina.", "error");
                    }
                } else {
                    alert(d.data);
                    $state.go("admision");
                }
            });
        }
        function _init() {
            GetCunaByAdmision();
        }
        //</editor-fold>
        _init();
    }]);



