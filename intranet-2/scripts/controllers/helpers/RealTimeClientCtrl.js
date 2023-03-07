//app.controller('WhoAdminCtrl', ["$scope","$rootScope",
//    function ($scope,$rootScope) {
//        var conn = new WebSocket('ws://192.168.8.125:8090/solicitud_mantenimiento');
//        conn.onopen = function (e) {
//            console.log("Connection established!");
//            conn.send(JSON.stringify({event: 'connect', is_admin: false, UsuarioId: $rootScope.username.UserId, msg: ""}));
//
//        };
//
//        conn.onmessage = function (e) {
//            var event = JSON.parse(e.data);
//            if (event.event !== 'connect' && event.event !== 'connected') {
//                $scope.cargado = false;
//                $scope.simpleTableOptions = null;
//                _init();
//                $scope.$apply();
//            }
//            console.log(e.data);
//        };
//
//        // botones
//        $scope.SendMensaje = function (Mensaje) {
//            if (Mensaje.length > 1) {
//                conn.send(JSON.stringify({
//                    event: 'foradmin',
//                    is_admin: false,
//                    UsuarioId: $rootScope.username.UserId,
//                    Envia: $rootScope.username.NombreCompleto,
//                    msg: $scope.Mensaje
//                }));
//                return true;
//            }else{
//                return false;
//            }
//        };
//    }]);





