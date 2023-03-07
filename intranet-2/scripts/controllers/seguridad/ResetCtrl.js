'use strict';
app.controller('ResetCtrl', ["$scope", "TokenService", "$state", "$stateParams", "UsuarioService",
    function ($scope, TokenService, $state, $stateParams, UsuarioService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.reset = {// objeto para la consulta
            pass: '',
            pass2: '',
            UsuarioId: $stateParams.UserId,
            Email: ''
        };
//        var conn = new WebSocket('ws://192.168.9.139:8080/chat');
//		conn.onopen = function(e) {
//                    console.log(e);
//		    console.log("Connection established!");
//		};
//
//		conn.onmessage = function(e) {
//                    swal("mensaje", e.data, "success");
//		    console.log(e.data);
//		};
        //</editor-fold>


        $scope.ResetPassword = function () {
            if ($scope.reset.pass === $scope.reset.pass2) {
                if ($scope.reset.pass.length >= 4) {
                    UsuarioService.PutUsuario($scope.reset).then(function (d) {
                        if (typeof d.data !== "string") {
                            swal("Enhorabuena!", "Se ha cambiado la contraseña.", "success");
                            $state.go("login");
                        } else {
                            swal("Hubo un Error", d.data, "error");
                        }
                    });
                } else {
                    swal("Hubo un Error", "La contraseña debe tener minimo 4 caracteres", "error");
                }
            } else {
                swal("Error", "Las contraseñas no son iguales", "error");
            }

        };
        function isValidToken(Token) {
            var obj = {
                Token: Token
            };
            TokenService.isValidToken(obj).then(function (d) {
                console.log(d.data)
                if (typeof d.data === "object") {
                    $scope.reset.Email = d.data.sub;
                } else {
                    $state.go("login");
                }
            });
        }
        function _init() {
            isValidToken($stateParams.token);
        }
        _init();
    }]);

