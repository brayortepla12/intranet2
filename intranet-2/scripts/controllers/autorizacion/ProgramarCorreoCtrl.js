app.controller('ProgramarCorreoCtrl', ["$scope", "$rootScope", "$state", "AutorizacionService",
    function ($scope, $rootScope, $state, AutorizacionService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Protocolos = [];
        $scope.Archivo = [];
        $scope.EnvioCorreo = {
            ProtocoloId: "",
            Mensaje: "",
            EmailSolicitante: $rootScope.username.Email,
            CreatedBy: $rootScope.username.NombreCompleto
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Guardar = function () {
            if ($scope.Archivo.length > 0) {
                var fd = new FormData();
                for (var i in $scope.Archivo) {
                    fd.append('file_' + i, $scope.Archivo[i]);
                }
                fd.append('EnvioCorreo', JSON.stringify($scope.EnvioCorreo));
                
                AutorizacionService.ProgramarEmail(fd).then(function (d) {
                    console.log(d.data);
                });
            }

//           
//            AutorizacionService.ProgramarEmail(fd).then(function (d) {
//                console.log(d.data);
//            });

        };

        $scope.NewEnvioCorreo = function () {
            $("#EnvioCorreoModal").modal('show');
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consulta">
        function GetProcesos() {
            AutorizacionService.getAllProtocoloAutorizacion().then(function (d) {
                $scope.Protocolos = d.data;
                $scope.cargado = true;
            });
        }

        function _init() {
            GetProcesos();
        }
        //</editor-fold>
        _init();
    }]);





