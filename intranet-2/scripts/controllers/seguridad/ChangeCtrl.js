'use strict';
app.controller('ChangeCtrl', ["$scope", "$rootScope", "UsuarioService",
    function ($scope, $rootScope, UsuarioService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.change = {// objeto para la consulta
            pass_c: '',
            pass2_c: '',
            UsuarioId_c: $rootScope.username.UserId,
            Email_c: $rootScope.username.Email
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


        $scope.ChangePassword = function () {
            if ($scope.change.pass_c === $scope.change.pass2_c) {
                if ($scope.change.pass_c.length >= 4) {
                    UsuarioService.PutUsuario($scope.change).then(function (d) {
                        if (typeof d.data !== "string") {
                            swal("Enhorabuena!", "Se ha cambiado la contraseña.", "success");
                            $scope.change = {// objeto para la consulta
                                pass_c: '',
                                pass2_c: '',
                                UsuarioId_c: $rootScope.username.UserId,
                                Email_c: $rootScope.username.Email
                            };
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


    }]);

